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

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/teacherOptions');
            else return redirect()->to('public/quiz/studentExams');

        }else $this->loadViews('login');
        
	}

    public function createUserSession($username, $userpassword){

        $userModel  = new UserModel();
        $user       = $userModel->where('username',$username)->findAll();

        if($user){

            $passw = preg_replace('([^A-Za-z0-9])','',$userpassword);

            if(password_verify($passw,$user[0]['userpassword'])){

                $_SESSION['user']['username']   = $user[0]['nameus'];
                $_SESSION['user']['iduser']     = $user[0]['iduser'];
                $_SESSION['user']['category']   = $user[0]['category'];

                $data['user'] = $user[0];

                if($user[0]['category'] == 'teacher') return redirect()->to('public/quiz/teacherOptions');
                else return redirect()->to('public/quiz/studentExams');

            }else{
                echo "Something went wrong!";
                return redirect()->to('public/quiz/loginUser');
            }

        }else{
            echo 'Something went wrong!';
            return redirect()->to('public/quiz/loginUser');
        }

    }

    public function loginUser(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/teacherOptions');
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

    public function teacherOptions(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') $this->loadViews('teacherOptions');
            else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

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
        $finishedExam     = $studentexamModel->where($arrayData)->find();

        return $finishedExam;
    }

    public function getExamTitle($idexam){

        $questionModel  = new QuestionModel();

        $title          = $questionModel->join('exams','exams.idexam = questions.idexam')
                                        ->where('questions.idexam',$idexam)
                                        ->find();

        return $title;

    }

    public function getTotalQuestions($idexam){

        $questionModel  = new QuestionModel();

        $totalquestions = $questionModel->where('questions.idexam',$idexam)
                                        ->countAllResults();

        return $totalquestions;

    }

    public function examStart(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/teacherOptions');
            else{

                if($_GET['idexam']){

                    $idexam             = $_GET['idexam'];
                    $title              = $this->getExamTitle($idexam);
                    $data['title']      = $title[0]['title'];
                    $finishedExam       = $this->isAFinishedExam($idexam);

                    if($finishedExam){ 

                        $data['score'] = $finishedExam[0]['score'];
                        
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

    public function getChoices($idquest){

        $choicesModel   = new ChoiceModel();
        $choices        = $choicesModel->where('idquest',$idquest)
                                       ->find();

        return $choices;
    }

    public function getExamQuestion($idexam, $idquest){

        $questionModel  = new QuestionModel();
        $arrayData      = array('questionnumber' => $idquest,
                                'idexam'         => $idexam);

        $questions      = $questionModel->where($arrayData)
                                        ->find();

        return $questions;
    }

    public function question(){

        if($_GET['idq'] && $_GET['idexam']){

                $idexam             = $_GET['idexam'];
                $idquest            = $_GET['idq'];
                $data['questions']  = $this->getExamQuestion($idexam, $idquest);
                $data['choices']    = $this->getChoices($idquest);
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

                                
                                $questionModel  = new QuestionModel();
                                $this->createQuestion($idexam,$_POST['question_text']);

                                $res = $questionModel->select('idquestion')
                                                     ->where('question',$_POST['question_text'])
                                                     ->find();

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

    public function findExam($title){

        $examModel  = new ExamModel();

        $res        = $examModel->select('idexam')
                                ->where('title',$title)
                                ->find();
        return $res;

    }

    public function addExam(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                $data['right'] = 0;
                $data['wrong'] = 0;

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
                            
                            $res = $this->findExam($_POST['title']);

                            if($res) $data['right'] = 1;
                            else $data['wrong'] = 1;

                        }else{
                            $errors         = $validation->getErrors();
                            $data['error']  = $errors;
                        }
                    $this->loadViews('addExam',$data);

                }else $this->loadViews('addExam',$data);

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