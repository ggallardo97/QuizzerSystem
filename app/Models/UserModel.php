<?php 
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
    
    protected $table      = 'users';
    protected $primaryKey = 'iduser';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['nameus','username','userpassword','email','category'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}

?>