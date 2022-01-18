<?php 
namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model{
    
    protected $table      = 'questions';
    protected $primaryKey = 'idquestion';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['question','idexam','questionnumber'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>