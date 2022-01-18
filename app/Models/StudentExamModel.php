<?php 
namespace App\Models;

use CodeIgniter\Model;

class StudentExamModel extends Model{
    
    protected $table      = 'student_exam';
    protected $primaryKey = 'idstudexam';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idstudent','idexam','dateexam','score'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>