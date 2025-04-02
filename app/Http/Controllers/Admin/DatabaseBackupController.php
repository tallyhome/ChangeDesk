<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DatabaseBackupController extends Controller
{
    /**
     * Affiche la page de gestion des sauvegardes de base de données
     */
    public function index()
    {
        // Récupérer la liste des sauvegardes existantes
        $backups = $this->getBackups();
        
        return view('admin.backups.index', compact('backups'));
    }

    /**
     * Crée une sauvegarde de la base de données
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        try {
            // Créer un nom de fichier unique pour la sauvegarde
            $type = $request->has('user_data_only') ? 'user_data_' : 'full_';
            $filename = $type . 'backup_' . Carbon::now()->format('Y-m-d_His') . '.sql';
            $storagePath = storage_path('app/backups');
            
            // Créer le répertoire s'il n'existe pas
            if (!file_exists($storagePath)) {
                if (!mkdir($storagePath, 0755, true)) {
                    throw new \Exception('Impossible de créer le répertoire de sauvegarde. Vérifiez les permissions.');
                }
            }
            
            // Chemin complet du fichier de sauvegarde
            $filePath = $storagePath . '/' . $filename;
            
            // Ouvrir le fichier en écriture
            $file = fopen($filePath, 'w');
            if (!$file) {
                throw new \Exception('Impossible de créer le fichier de sauvegarde. Vérifiez les permissions.');
            }
            
            // Écrire l'en-tête du fichier SQL
            fwrite($file, "-- Sauvegarde de la base de données générée par l'application\n");
            fwrite($file, "-- Date: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n");
            fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n\n");
            
            // Récupérer la liste des tables à sauvegarder
            $tables = [];
            if ($request->has('user_data_only')) {
                // Tables contenant uniquement les données utilisateur
                $tables = ['users', 'bug_reports', 'todo_items', 'wiki_articles', 'wiki_categories', 'visits'];
            } else {
                // Toutes les tables de la base de données
                $tables = DB::select('SHOW TABLES');
                $tables = array_map(function($table) {
                    return reset($table); // Convertir l'objet en string (nom de la table)
                }, $tables);
            }
            
            // Pour chaque table, générer les instructions SQL
            foreach ($tables as $table) {
                // Structure de la table
                fwrite($file, "\n-- Structure de la table `{$table}`\n\n");
                
                // Récupérer la structure de la table
                $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
                if (!empty($createTable)) {
                    $createTableSql = $createTable[0]->{'Create Table'} ?? '';
                    if ($createTableSql) {
                        fwrite($file, $createTableSql . ";\n\n");
                    }
                }
                
                // Données de la table
                fwrite($file, "-- Données de la table `{$table}`\n\n");
                
                // Récupérer les données par lots pour éviter les problèmes de mémoire
                $offset = 0;
                $limit = 500; // Nombre de lignes par lot
                
                do {
                    $rows = DB::table($table)->offset($offset)->limit($limit)->get();
                    $count = count($rows);
                    
                    if ($count > 0) {
                        // Récupérer les noms des colonnes
                        $columns = array_keys((array)$rows[0]);
                        $columnNames = '`' . implode('`, `', $columns) . '`';
                        
                        // Générer les instructions INSERT
                        foreach ($rows as $row) {
                            $values = [];
                            foreach ((array)$row as $value) {
                                if (is_null($value)) {
                                    $values[] = 'NULL';
                                } else if (is_numeric($value)) {
                                    $values[] = $value;
                                } else {
                                    $values[] = "'" . str_replace("'", "\\'", $value) . "'";
                                }
                            }
                            
                            fwrite($file, "INSERT INTO `{$table}` ({$columnNames}) VALUES (" . implode(", ", $values) . ");\n");
                        }
                    }
                    
                    $offset += $limit;
                } while ($count > 0);
                
                fwrite($file, "\n");
            }
            
            // Réactiver les contraintes de clé étrangère
            fwrite($file, "SET FOREIGN_KEY_CHECKS=1;\n");
            
            // Fermer le fichier
            fclose($file);
            
            // Vérifier que le fichier a bien été créé et n'est pas vide
            if (!file_exists($filePath) || filesize($filePath) === 0) {
                throw new \Exception('Le fichier de sauvegarde n\'a pas pu être créé ou est vide.');
            }
            
            $backupType = $request->has('user_data_only') ? 'des données utilisateur' : 'complète';
            return redirect()->route('admin.backups.index')
                ->with('success', "Sauvegarde {$backupType} créée avec succès.");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Télécharge une sauvegarde
     */
    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            return response()->download($path);
        }
        
        return redirect()->route('admin.backups.index')
            ->with('error', 'Le fichier de sauvegarde n\'existe pas.');
    }

    /**
     * Supprime une sauvegarde
     */
    public function destroy($filename)
    {
        $path = storage_path('app/backups/' . $filename);
        
        if (file_exists($path)) {
            unlink($path);
            return redirect()->route('admin.backups.index')
                ->with('success', 'Sauvegarde supprimée avec succès.');
        }
        
        return redirect()->route('admin.backups.index')
            ->with('error', 'Le fichier de sauvegarde n\'existe pas.');
    }

    /**
     * Restaure une sauvegarde
     */
    public function restore($filename)
    {
        try {
            $path = storage_path('app/backups/' . $filename);
            
            if (!file_exists($path)) {
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Le fichier de sauvegarde n\'existe pas.');
            }
            
            // Lire le contenu du fichier SQL
            $sql = file_get_contents($path);
            if (!$sql) {
                return redirect()->route('admin.backups.index')
                    ->with('error', 'Impossible de lire le fichier de sauvegarde.');
            }
            
            // Désactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Diviser le fichier SQL en requêtes individuelles
            $queries = $this->splitSqlFile($sql);
            
            // Exécuter chaque requête
            foreach ($queries as $query) {
                try {
                    if (trim($query) != '') {
                        DB::unprepared($query);
                    }
                } catch (\Exception $e) {
                    // Continuer même si une requête échoue
                    // On pourrait enregistrer les erreurs dans un log ici
                }
            }
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return redirect()->route('admin.backups.index')
                ->with('success', 'Base de données restaurée avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
    
    /**
     * Divise un fichier SQL en requêtes individuelles
     * 
     * @param string $sql Le contenu du fichier SQL
     * @return array Les requêtes individuelles
     */
    private function splitSqlFile($sql)
    {
        // Supprimer les commentaires
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = preg_replace('/--.*?\n/', '\n', $sql);
        
        // Diviser le fichier en requêtes individuelles
        $queries = [];
        $currentQuery = '';
        
        foreach (explode("\n", $sql) as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $currentQuery .= $line . "\n";
            
            // Si la ligne se termine par un point-virgule, c'est la fin d'une requête
            if (substr($line, -1) === ';') {
                $queries[] = $currentQuery;
                $currentQuery = '';
            }
        }
        
        // Ajouter la dernière requête si elle existe
        if (!empty($currentQuery)) {
            $queries[] = $currentQuery;
        }
        
        return $queries;
    }

    /**
     * Récupère la liste des sauvegardes disponibles
     */
    private function getBackups()
    {
        $backups = [];
        $backupPath = storage_path('app/backups');
        
        if (file_exists($backupPath)) {
            $files = scandir($backupPath);
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && Str::endsWith($file, '.sql')) {
                    $backups[] = [
                        'filename' => $file,
                        'size' => $this->formatSize(filesize($backupPath . '/' . $file)),
                        'date' => Carbon::createFromTimestamp(filemtime($backupPath . '/' . $file))->format('d/m/Y H:i:s')
                    ];
                }
            }
            
            // Trier les sauvegardes par date (la plus récente en premier)
            usort($backups, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        return $backups;
    }

    /**
     * Formate la taille du fichier en unités lisibles
     */
    private function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }
}