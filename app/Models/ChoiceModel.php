<?php 
namespace App\Models;

use CodeIgniter\Model;

class ChoiceModel extends Model{
    
    protected $table      = 'choices';
    protected $primaryKey = 'idchoice';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['idquest','iscorrect','choice'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deletedc';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>