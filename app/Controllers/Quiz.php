<?php

namespace App\Controllers;
use App\Models\QuestionModel;
use App\Models\ChoiceModel;
use App\Models\UserModel;
use App\Models\ExamModel;
use App\Models\StudentExamModel;

class Quiz extends BaseController{

    protected $session;

    public function __construct(){
        $this->session = \Config\Services::session();
        $this->session->start();
    }

    public function index(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/showExams');
            else return redirect()->to('public/quiz/studentExams');

        }else $this->loadViews('login');
        
	}

    public function createUser($nameus,$username,$email,$userpassword){

        $userModel      = new UserModel();
        $hashPassword   = password_hash($userpassword,PASSWORD_DEFAULT);

        $dataUser = [
            'nameus'        => $nameus,
            'username'      => $username,
            'userpassword'  => $hashPassword,
            'email'         => $email,
            'category'      => 'student'
        ];

        $userModel->insert($dataUser);

    }

    public function getUser($username){

        $userModel  = new UserModel();
        $user       = $userModel->where('username',$username)->find();

        return $user;
    }

    public function validatePassword($username, $userpassword){

        $user = $this->getUser($username);

        if($user){

            $passw = preg_replace('([^A-Za-z0-9])','',$userpassword);

            if(password_verify($passw,$user[0]['userpassword'])) return true;
            else return false;

        }else return false;

    }

    public function createUserSession($username, $userpassword){

        if($this->validatePassword($username, $userpassword)){

            $user = $this->getUser($username);

            $_SESSION['user']['username']   = $user[0]['nameus'];
            $_SESSION['user']['iduser']     = $user[0]['iduser'];
            $_SESSION['user']['category']   = $user[0]['category'];

            if($user[0]['category'] == 'teacher') return redirect()->to('public/quiz/showExams');
            else return redirect()->to('public/quiz/studentExams');

        }else{
                echo "Something went wrong!";
                return redirect()->to('public/quiz/loginUser');
            }

    }

    public function loginUser(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/showExams');
            else return redirect()->to('public/quiz/studentExams');

        }else{

            helper(['url','form']);
            $validation = \Config\Services::validation();

            $validation->setRules([
                'username'      => 'required',
                'userpassword'  => 'required'
            ],[
                'username' =>[
                    'required' => 'User is required!'
                ],
                'userpassword' =>[
                    'required' => 'Password is required!'
                ]
            ]);

            if($_POST){

                if($validation->withRequest($this->request)->run()){

                    return $this->createUserSession($_POST['username'],$_POST['userpassword']);

                }else{
                    
                    $errors         = $validation->getErrors();
                    $data['error']  = $errors;
                    $this->loadViews('login',$data);
                }
            }else{
                $this->loadViews('login');
            }
        }
    }

    public function logout(){

        unset($_SESSION['user']);
        session_destroy();

        return redirect()->to(base_url().'/public/quiz/index');
    }

    public function registerUser(){

        helper(['url','form']);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nameus'        => 'required',
            'username'      => 'required',
            'userpassword'  => 'required',
            'email'         => 'required'
        ],[
            'nameus' =>[
                'required' => 'Name is required!'
            ],
            'username' =>[
                'required' => 'User is required!'
            ],
            'userpassword' =>[
                'required' => 'Password is required!'
            ],
            'email' =>[
                'required' => 'Email is required!'
            ]
        ]);

        if($_POST){

            if($validation->withRequest($this->request)->run()){

                $this->createUser($_POST['nameus'],$_POST['username'],$_POST['email'],$_POST['userpassword']);
                return $this->createUserSession($_POST['username'],$_POST['userpassword']);
                
            }else{
                $errors         = $validation->getErrors();
                $data['error']  = $errors;
                $this->loadViews('register',$data);
            }
        }else{
            $this->loadViews('register');
        }
    }

    public function getExams(){

        $examModel      = new ExamModel();
        $exams          = $examModel->findAll();

        return $exams;

    }

    public function showExams(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                $data['exams'] = [];
                $exams = $this->getExams();
                
                if($exams) $data['exams'] = $exams;
                
                $this->loadViews('showExams',$data);
            
            }
            else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

    }

    public function getQuestions($idexam){

        $questions         = new QuestionModel();
        $totalQuestions    = $questions->join('exams', 'exams.idexam = questions.idexam')
                                       ->where('questions.idexam',$idexam)
                                       ->orderBy('idquestion', 'ASC')
                                       ->findAll();

        return $totalQuestions;
    }

    public function showQuestions(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                if($_GET['idexam']){

                    $idexam             = $_GET['idexam'];
                    $data['questions']  = [];
                    $totalQuestions     = $this->getQuestions($idexam);

                    if($totalQuestions) $data['questions'] = $totalQuestions;

                    $this->loadViews('showQuestions',$data);

                }else return redirect()->to('public/quiz/showExams');

            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

    }

    public function deleteChoices($idquest){

        $choiceModel   = new ChoiceModel();
        $choiceModel->where('idquest',$idquest)
                    ->delete();

    }

    public function deleteQuestion(){

        if(isset($_POST)){

            $questionModel = new QuestionModel();
            $arrayData     = array('idquestion' => $_POST['id'],
                                    'idexam'    => $_POST['idexam']);

            $questionModel->where($arrayData)
                          ->delete();

            $this->deleteChoices($_POST['id']);

            $status = "QUESTION DELETED";

        }else $status = "ERROR";

        echo ($status);die;
    }

    public function editQuestion(){

        if(isset($_POST)){

            $questionModel = new QuestionModel();

            $arrayData     = array('idquestion' => $_POST['id'],
                                    'idexam'    => $_POST['idexam']);
            
            $questionModel->set('question', $_POST['content'])
                          ->where($arrayData)
                          ->update();

            $status = "QUESTION MODIFIED!";

        }else $status = "ERROR";

        echo ($status);die;

    }

    public function studentExams(){

        $examModel      = new ExamModel();
        $data['exams']  = [];
        $totalExams     = $examModel->findAll();

        if($totalExams) $data['exams'] = $totalExams;

        $this->loadViews('studentExams',$data);
                
    }

    public function isAFinishedExam($idexam){

        $studentexamModel = new StudentExamModel();

        $arrayData        = array('idstudent' => $_SESSION['user']['iduser'],
                                  'idexam'    => $idexam);
        $finishedExam     = $studentexamModel->where($arrayData)->first();

        return $finishedExam;
    }

    public function getExamTitle($idexam){

        $questionModel  = new QuestionModel();

        $title          = $questionModel->join('exams','exams.idexam = questions.idexam')
                                        ->where('questions.idexam',$idexam)
                                        ->first();

        return $title;

    }

    public function getTotalQuestions($idexam){

        $questionModel  = new QuestionModel();

        $totalquestions = $questionModel->where('questions.idexam',$idexam)
                                        ->countAllResults();

        return $totalquestions;

    }

    public function getChoices($idquest){

        $choicesModel   = new ChoiceModel();
        $choices        = $choicesModel->where('idquest',$idquest)
                                       ->orderBy('idchoice')
                                       ->findAll();

        return $choices;
    }

    public function getExamQuestion($idexam, $idquest){

        $questionModel  = new QuestionModel();
        $arrayData      = array('questionnumber' => $idquest,
                                'idexam'         => $idexam);

        $questions      = $questionModel->where($arrayData)
                                        ->first();

        return $questions;
    }

    public function examStart(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/showExams');
            else{

                if($_GET['idexam']){

                    $idexam             = $_GET['idexam'];
                    $title              = $this->getExamTitle($idexam);
                    $data['title']      = $title['title'];
                    $finishedExam       = $this->isAFinishedExam($idexam);

                    if($finishedExam){ 

                        $data['score'] = $finishedExam['score'];
                        
                        return $this->loadViews('examFinished',$data);

                    }else{

                        $totalquestions = $this->getTotalQuestions($idexam);
                        $data['totalq'] = $totalquestions;
                        $data['idexam'] = $idexam;
                   
                        $this->loadViews('index',$data);
                    }
                }
            }
        }else return redirect()->to('public/quiz/loginUser');
    }

    public function question(){

        if($_GET['idq'] && $_GET['idexam']){

                $idexam             = $_GET['idexam'];
                $idquest            = $_GET['idq'];
                $data['questions']  = $this->getExamQuestion($idexam, $idquest);
                $idq                = $data['questions']['idquestion'];
                $data['choices']    = $this->getChoices($idq);
                $data['totalq']     = $this->getTotalQuestions($idexam);
                $data['idq']        = $idquest;
                $data['idexam']     = $idexam;
                
                $this->loadViews('question',$data); 
        }
    }

    public function getChoice($idchoice){

        $choicesModel   = new ChoiceModel();
        $choice         = $choicesModel->where('idchoice',$idchoice)
                                       ->find();

        return $choice;

    }

    public function process(){

        if(isset($_POST)){

            if(!isset($_SESSION['score'])) $_SESSION['score'] = 0;

            if($_GET['idexam']){

                $idexam         = $_GET['idexam'];
                
                $totalquestions = $this->getTotalQuestions($idexam);

                $select = $_POST['question_id'];
                $nextq  = $_POST['next_question'];
                $nextq++;

                $choice = $this->getChoice($select);

                if($choice[0]['iscorrect'] == 1) $_SESSION['score']++;

                $data['correct'] = $_SESSION['score'];

                if($nextq > $totalquestions){

                    $this->registerScore($_SESSION['user']['iduser'],$_SESSION['score'],$idexam);

                    $this->loadViews('final',$data);

                }else return redirect()->to('/public/quiz/question?idq='.$nextq.'&idexam='.$idexam);

            }
        }
    }

    public function registerScore($iduser, $score, $idexam){

        $studentexamModel = new StudentExamModel();

        $result = [
            'idstudent' => $iduser,
            'idexam'    => $idexam,
            'dateexam'  => date("Y-m-d"),
            'score'     => $score
        ];

        $studentexamModel->insert($result);

        unset($_SESSION['score']);

    }

    public function createQuestion($idexam,$questioncontent){

        $questionModel  = new QuestionModel();
        $total          = $this->getTotalQuestions($idexam);
        $total++;

        $dataQuestion   = [
            'idexam'            => $idexam,
            'question'          => $questioncontent,
            'questionnumber'    => $total
        ];

        $questionModel->insert($dataQuestion);

    }

    public function createChoices($choicesArray, $iscorrect,$idquestion){

        $choicesModel = new ChoiceModel();

        foreach ($choicesArray as $key => $choices){

            $correct = 0;
            if($key == ($iscorrect - 1)) $correct = 1;

            $dataChoice = [
                'idquest'   => $idquestion,
                'iscorrect' => $correct,
                'choice'    => $choices
            ];

            $choicesModel->insert($dataChoice);
        }

    }

    public function getQuestionByContent($content){

        $questionModel  = new QuestionModel();
        $res            = $questionModel->select('idquestion')
                                        ->where('question',$content)
                                        ->find();

        return $res;
    }

    public function addQuestion(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                if($_GET['idexam']){

                    $data['right']  = 0;
                    $data['wrong']  = 0;
                    $data['idexam'] = $_GET['idexam'];
                    $idexam         = $_GET['idexam'];

                    if(isset($_POST)){

                        helper(['url','form']);

                            $validation = \Config\Services::validation();
                            $validation->setRules([
                                'question_text' => 'required',
                                ],[
                                    'question_text' =>[
                                        'required'  => 'Question is required!'
                                    ]
                                ]);

                            if($validation->withRequest($this->request)->run()){

                                $this->createQuestion($idexam,$_POST['question_text']);

                                $res = $this->getQuestionByContent($_POST['question_text']);

                                if($res) $data['right'] = 1;
                                else $data['wrong'] = 1;

                                $this->createChoices($_POST['choices'],$_POST['iscorrect'],$res[0]['idquestion']);

                            }else{
                                $errors = $validation->getErrors();
                                $data['error'] = $errors;
                            }
                        $this->loadViews('add',$data);

                    }else $this->loadViews('add',$data);

                }else return redirect()->to('public/quiz/showExams');

            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');
    }

    public function createExam($title){

        $examModel  = new ExamModel();

        $dataExam   = [
            'title' => $title,
        ];

        $examModel->insert($dataExam);

    }

    public function getExam($title){

        $examModel  = new ExamModel();

        $res        = $examModel->select('idexam')
                                ->where('title',$title)
                                ->first();
        return $res;

    }

    public function addExam(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                if(isset($_POST)){

                    helper(['url','form']);

                    $validation = \Config\Services::validation();
                    $validation->setRules([
                        'title' => 'required',
                    ],[
                        'title' =>[
                            'required'  => 'Title is required!'
                        ]
                    ]);

                    if($validation->withRequest($this->request)->run()){

                        $this->createExam($_POST['title']);

                        $status = "EXAM ADDED";

                    }else $status = "ERROR";
                    
                }else $status = "ERROR";

                echo ($status);die;

            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');
    }

    public function getScores(){

        $studentexamModel   = new StudentExamModel();

        $scores = $studentexamModel->select('nameus,score,dateexam,title')
                                   ->join('users', 'users.iduser = student_exam.idstudent')
                                   ->join('exams', 'exams.idexam = student_exam.idexam')
                                   ->find();
        return $scores;

    }

    public function showScores(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                $data['scores'] = [];

                $scores = $this->getScores();

                if($scores) $data['scores'] = $scores;

                $this->loadViews('showScores',$data);
                
            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

    }

    public function editChoice(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                if(isset($_POST)){

                    $choicesModel = new ChoiceModel();
                    $choicesModel->set('choice',$_POST['content'])
                                 ->where('idchoice',$_POST['idchoice'])
                                 ->update();

                    $status = "CHOICE MODIFIED";

                }else $status = "ERROR";

                echo ($status);
                die;

            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

    }

    public function showChoices(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                if(isset($_POST)){

                    $choices = $this->getChoices($_POST['idquestion']);

                    if($choices){

                        foreach($choices as $key => $row){

                            echo "<label>Choice [".($key+1)."]</label>";
                            echo "<button data-idchoice='".$row['idchoice']."' style='margin-left: 363px;' class='btn btn-primary btn-sm fa fa-edit editChoicesButton'></button>";
                            echo "<input type='text' class='form-control' data-idchoice='".$row['idchoice']."' id='choiceContent".$row['idchoice']."' value='".$row['choice']."' required/>";
                            
                        }

                    }
                }
            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');
    }

    public function loadViews($view = null, $data = null){

        if($data){

            echo view('includes/header',$data);
            echo view($view,$data);
            echo view('includes/footer',$data);

        }else{

            echo view('includes/header');
            echo view($view);
            echo view('includes/footer');
        }
        
    }
}
?>