<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class ScoreModel extends Model
{
    protected $table = 'newscoresheet';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user', 'quiz', 'score', 'sent', 'answers', 
        'created_at', 'updated_at', 'deleted_at'
    ];
 
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}