<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class QuizModel extends Model
{
    protected $table = 'quiz';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'code', 'title', 'description', 'published', 
        'questions', 'answers', 'created_at', 'updated_at', 'deleted_at'
    ];
 
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}