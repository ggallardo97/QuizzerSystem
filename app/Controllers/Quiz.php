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

                    $userModel  = new UserModel();
                    $user       = $userModel->where('username',$_POST['username'])->findAll();

                    if($user){

                        $passw = preg_replace('([^A-Za-z0-9])','',$_POST['userpassword']);

                        if(password_verify($passw,$user[0]['userpassword'])){

                            $_SESSION['user']['username']   = $user[0]['nameus'];
                            $_SESSION['user']['iduser']     = $user[0]['iduser'];
                            $_SESSION['user']['category']   = $user[0]['category'];

                            $data['user'] = $user[0];

                            if($user[0]['category'] == 'teacher') return redirect()->to('public/quiz/teacherOptions');
                            else return redirect()->to('public/quiz/studentExams');

                        }else{
                            echo "Something went wrong!";
                            $this->loadViews('login');
                        }

                    }else{
                        echo 'Something went wrong!';
                        $this->loadViews('login');
                    }

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

    public function showExams(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                $examModel      = new ExamModel();
                $data['exams']  = [];
                $exams          = $examModel->findAll();
                
                if($exams) $data['exams'] = $exams;
                
                $this->loadViews('showExams',$data);
            
            }
            else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

    }

    public function showQuestions(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                if($_GET['idexam']){

                    $idexam = $_GET['idexam'];

                    $questions         = new QuestionModel();
                    $totalQuestions    = $questions->join('exams', 'exams.idexam = questions.idexam')
                                                   ->where('questions.idexam',$idexam)
                                                   ->orderBy('idquestion', 'ASC')
                                                   ->findAll();

                    $data['questions'] = $totalQuestions;

                    $this->loadViews('showQuestions',$data);

                }else return redirect()->to('public/quiz/showExams');

            }else return redirect()->to('public/quiz/studentExams');

        }else return redirect()->to('public/quiz/loginUser');

    }

    public function deleteQuestion(){

        if(isset($_POST)){

            $questionModel = new QuestionModel();
            $choiceModel   = new ChoiceModel();
            $arrayData     = array('idquestion' => $_POST['id'],
                                    'idexam'    => $_POST['idexam']);

            $questionModel->where($arrayData)
                          ->delete();

            $choiceModel->where('idquest',$_POST['id'])
                        ->delete();

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

                $userModel      = new UserModel();
                $hashPassword   = password_hash($_POST['userpassword'],PASSWORD_DEFAULT);

                $dataU = [
                    'nameus'        => $_POST['nameus'],
                    'username'      => $_POST['username'],
                    'userpassword'  => $hashPassword,
                    'email'         => $_POST['email'],
                    'category'      => 'student'
                ];

                $userModel->insert($dataU);
                $res = $userModel->where('username',$_POST['username'])->find();

                $_SESSION['user']['username']   = $_POST['nameus'];
                $_SESSION['user']['iduser']     = $res[0]['iduser'];
                $_SESSION['user']['category']   = $res[0]['category'];

                return redirect()->to('public/quiz/studentExams');
                
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

    public function examStart(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher') return redirect()->to('public/quiz/teacherOptions');
            else{

                if($_GET['idexam']){

                    $idexam           = $_GET['idexam'];
                    $studentexamModel = new StudentExamModel();
                    $questionModel    = new QuestionModel();

                    $arrayData        = array('idstudent' => $_SESSION['user']['iduser'],
                                              'idexam'    => $idexam);
                    $finishedExam     = $studentexamModel->where($arrayData)->find();

                    $title            = $questionModel->join('exams','exams.idexam = questions.idexam')
                                                      ->where('questions.idexam',$idexam)
                                                      ->find();

                    $data['title']    = $title[0]['title'];

                    if($finishedExam){ 

                        $data['score'] = $finishedExam[0]['score'];
                        
                        return $this->loadViews('examFinished',$data);

                    }else{

                        $totalquestions = $questionModel->where('questions.idexam',$idexam)
                                                        ->countAllResults();
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

                $idexam         = $_GET['idexam'];
                $idq            = $_GET['idq'];
                $questionModel  = new QuestionModel();
                $choicesModel   = new ChoiceModel();
                $arrayData      = array('questionnumber' => $idq,
                                        'idexam'         => $idexam);

                $totalquestions = $questionModel->where('idexam',$idexam)
                                                ->countAllResults();

                $questions = $questionModel->where($arrayData)
                                           ->find();

                $choices = $choicesModel->where('idquest',$idq)
                                        ->find();

                $data['questions']  = $questions;
                $data['choices']    = $choices;
                $data['idq']        = $idq;
                $data['idexam']     = $idexam;
                $data['totalq']     = $totalquestions;

                $this->loadViews('question',$data); 

        }
    }

    public function process(){

        if(isset($_POST)){

            if(!isset($_SESSION['score'])) $_SESSION['score'] = 0;

            if($_GET['idexam']){

                $idexam         = $_GET['idexam'];
                $questionModel  = new QuestionModel();
                $choicesModel   = new ChoiceModel();

                $totalquestions = $questionModel->where('idexam',$idexam)
                                                ->countAllResults();

                $select = $_POST['question_id'];
                $nextq  = $_POST['next_question'];
                $nextq++;

                $choice = $choicesModel->where('idchoice',$select)
                                       ->find();

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
                                $choicesModel   = new ChoiceModel();

                                $dataQuestion = [
                                    'idexam'   => $idexam,
                                    'question' => $_POST['question_text'],
                                ];

                                $questionModel->insert($dataQuestion);

                                $res = $questionModel->select('idquestion')
                                                     ->where('question',$_POST['question_text'])
                                                     ->find();

                                if($res) $data['right'] = 1;
                                else $data['wrong'] = 1;

                                foreach ($_POST['choices'] as $key => $choices){

                                    $correct = 0;
                                    if($key == ($_POST['iscorrect'] - 1)) $correct = 1;

                                    $dataChoice = [
                                        'idquest'   => $res[0]['idquestion'],
                                        'iscorrect' => $correct,
                                        'choice'    => $choices
                                    ];

                                    $choicesModel->insert($dataChoice);
                                }

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

                            $examModel  = new ExamModel();

                            $dataExam = [
                                'title' => $_POST['title'],
                            ];

                            $examModel->insert($dataExam);

                            $res = $examModel->select('idexam')
                                             ->where('title',$_POST['title'])
                                             ->find();

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

    public function showScores(){

        if(isset($_SESSION['user'])){

            if($_SESSION['user']['category'] == 'teacher'){

                $studentexamModel   = new StudentExamModel();
                $data['scores']     = [];

                $scores = $studentexamModel->select('nameus,score,dateexam,title')
                                           ->join('users', 'users.iduser = student_exam.idstudent')
                                           ->join('exams', 'exams.idexam = student_exam.idexam')
                                           ->find();

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