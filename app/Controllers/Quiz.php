<?php

namespace App\Controllers;

class Quiz extends BaseController
{
	public function index()
	{
		// echo view('welcome_message');
		return redirect()->to(base_url('access'));
	}
	public function quizcode()
	{
		$var = new \App\Models\Variables();
		$data = [
			'quizinput' => $var->where('key', 'quizinput')->find()[0]['value'],
			'owner' => $var->where('key', 'owner')->find()[0]['value'],
		];
		$hdata = [
				'owner' => $var->where('key', 'owner')->find()[0]['value'],
			];
		echo view('quiz/nheader', $hdata);
		echo view('quiz/access', $data);
		echo view('quiz/nfooter');
	}
	public function message($msg, $title = '')
	{
		$data = [
			'msg' => $msg,
			'title' => $title,
		];
		echo view('quiz/nheader');
		echo view('quiz/msg', $data);
		echo view('quiz/nfooter');
	}
	public function processcode()
	{
		$incoming = $this->request->getGet();
		// $code = $incoming['code'];
		$session = session();


		$quizlet = new \App\Models\Quiz();
		if (!empty($db = $quizlet->where($incoming)->find())) {
			$res = $db[0];

			$ses_data = [
				'quiz' => $res['id'],
				'code' => $res['code'],
				'title' => $res['title'],
				'description' => $res['description'],
				'published' => $res['published']
			];
			$session->set($ses_data);
			return redirect()->to(base_url('/login'));
		} else {
			$this->message('The Quiz code you entered is incorrect');
		}
	}

	public function login()
	{
		$session = session();
		if ($session->code == '') {
			return redirect()->to(base_url('/access'));
		} else {
			echo view('quiz/nheader');
			echo view('quiz/login');
			echo view('quiz/nfooter');
		}
	}

	public function questions()
	{
		$session = session();
		if ($session->Logged_in != true) {
			return redirect()->to(base_url('/login'));
		} else {
			$quizlet = new \App\Models\Quiz();
			$var = new \App\Models\Variables();

			$qn = $var->where('key', 'qNumber')->find()[0]['value'];
			$timer = $var->where('key', 'timer')->find()[0]['value'];

			$code = $session->code;

			$res = $quizlet->where('code', $code)->find()[0];
			$res['qn'] = $qn;
			$res['timer'] = $timer;
			echo view('quiz/question', $res);
		}
	}

	public function postquiz()
	{
		$session = session();
		$quizlet = new \App\Models\Quiz();
		$var = new \App\Models\Variables();

		$scoresheet = new \App\Models\Scoresheet();
		$code = $session->code;
		$qn = $var->where('key', 'qNumber')->find()[0]['value'];
		$incoming = $this->request->getPost();
		$numbs = range(0, ($qn-1));
		$score = 0;

        // var_dump($incoming);

		$res = $quizlet->where('code', $code)->find()[0]['answers'];
		foreach (json_decode($res) as $key => $ans) {
			if (!empty($incoming[$key . 'que' . $key])) {
				if ($incoming[$key . 'que' . $key] == strtolower($ans->ans)) {
					$score++;
				} else {
					$score = $score + 0;
				}
			} else {
				$score = $score + 0;
			}
		}
		// Push the score to the broadsheet

		if (!empty($db = $scoresheet->where(['user' => $session->user, 'quiz' => $session->quiz])->find())) {
			if ($score > $db[0]['score']) {
				$scoresheet->update($db[0]['id'], ['score' => $score, 'answers'=>json_encode($incoming)]);
			}
		} else {
			$data = ['user' => $session->user, 'quiz' => $session->quiz, 'score' => $score, 'sent' => $session->published, 'answers'=>json_encode($incoming)];
			$scoresheet->insert($data);
		}

		if ($session->published) {
			$this->message("Your total score of ".$qn." is " . $score);
			$this->logout();
		} else {
			$this->message("Your Quiz has been submitted. You will receive your score via email");
			$this->logout();
		}
	}

	public function test($code, $do)
	{
		if ($do) {
			$key = 'zqu1eysapto';
			$pos = str_split($code);
			$res = '';
			foreach ($pos as $ky => $ps) {
				$res = $res . str_split($key)[$ps];
			}
			return urlencode($res);
		} else {
			$key = 'zqu1eysapto';
			$pos = urldecode($code);
			$res = '';
			$pos = str_split($pos);
			$key = str_split($key);
			foreach ($pos as $k => $os) {
				foreach ($key as $ky => $ps) {
					if ($pos[$k] == $ps)
						$res = $res . $ky;
				}
			}
			return $res;
		}
	}

	public function genSolution($code)
	{
		$coo = $this->test($code,1);
		echo "<a href='".base_url('/solution/'.$coo)."'>".base_url('/solution/'.$coo)."</a>";
	}

	public function solution($id)
	{

        $session = session();
        if ($session->Logged_in != true) {
            echo view('quiz/nheader');
            echo view('quiz/login2',['id'=>$id]);
            echo view('quiz/nfooter');
        } else {
            $id = $this->test($id, 0);

            echo ('<center>Solution for quiz '.$id.'</center><br>');
    		// echo ('<center>Solution for quiz '.$id.'</center><br>');
            $scoresheet = new \App\Models\Scoresheet();
    		$quizlet = new \App\Models\Quiz();
            $result = $quizlet->where('code', $id)->find();
            $res = $result[0]['answers'];
            $que = $result[0]['questions'];
            $id = $result[0]['id'];

            $uAns = [];
            $db = $scoresheet->where(['user' => $session->user, 'quiz' => $id])->find();
            if(!empty($db[0]['answers'])){
                $uAns = json_decode($db[0]['answers']);
                echo ('<center>You Scored <h2>'.$db[0]['score'].'</h2></center><br>');
                echo ("<center><a href='".base_url('logout')."'>Logout</a></center><br>");
            }else{
                echo ("<center>User's Answers not recorded</center><br>");
            }
    		// $id = md
            // var_dump($uAns);
    		foreach (json_decode($que) as $ky => $qus) {
    			echo ('('.($ky + 1) . ') ' . $qus->{0} . '<br>');
    			$option = [
    				'a' => $qus->{1},
    				'b' => $qus->{2},
    				'c' => $qus->{3},
    				'd' => $qus->{4},
    			];
    			foreach (json_decode($res) as $ke => $ans) {
    				if ($qus->id == $ans->id) {
    					foreach ($option as $key => $opt) {
    						echo ($key.') '.$opt);
                            $vl = $ke.'que'.$ke;

    						if ($ans->ans == $key) {
                                echo (' &#9989;');
                                // if($uAns->$vl == $key){
                                //     echo (' &#9989;');
                                // }
    						}else{
                               if($uAns->$vl == $key){
                                    echo (' &#10060;');
                                }
                            }
    						echo '<br>';
    					}
    					echo '<br>';
    				}
    			}
    		}
        }
	}

	public function probe($id)
	{
            
            // $id = $this->test($id, 0);

    		// echo ('<center>Solution for quiz '.$id.'</center><br>');
            $scoresheet = new \App\Models\Scoresheet();
    		$quizlet = new \App\Models\Quiz();
    		$users = new \App\Models\Users();

			$udata = $scoresheet->where('id', $id)->first();
			$participant = $users->where('id', $udata['user'])->first();

            $result = $quizlet->where('id', $udata['quiz'])->find();
            $res = $result[0]['answers'];
            $que = $result[0]['questions'];
            // $id = $result[0]['id'];

            echo ('<center>'.$participant['username'].' Solution for '.$result[0]['title'].'</center><br>');

            $uAns = [];
            $db = $scoresheet->where('id',$id)->find();
            if(!empty($db[0]['answers'])){
                $uAns = json_decode($db[0]['answers']);
				echo ('<b> Chosen Answer </b> <br>');
				var_dump($uAns);
				echo ('<br> <b> Programmed Answer </b> <br>');
				var_dump($res);
                echo ('<center>You Scored <h2>'.$udata['score'].'</h2></center><br>');
            }else{
                echo ("<center>User's Answers not recorded</center><br>");
            }
    		// $id = md
            // var_dump($uAns);
    		foreach (json_decode($que) as $ky => $qus) {
    			echo ('('.($ky + 1) . ') ' . $qus->{0} . '<br>');
    			$option = [
    				'a' => $qus->{1},
    				'b' => $qus->{2},
    				'c' => $qus->{3},
    				'd' => $qus->{4},
    			];
    			foreach (json_decode($res) as $ke => $ans) {
    				if ($qus->id == $ans->id) {
    					foreach ($option as $key => $opt) {
    						echo ($key.') '.$opt);
                            $vl = $ke.'que'.$ke;

    						if ($ans->ans == $key) {
                                echo (' &#9989;');
                                // if($uAns->$vl == $key){
                                //     echo (' &#9989;');
                                // }
    						}else{
                               if($uAns->$vl == $key){
                                    echo (' &#10060;');
                                }
                            }
    						echo '<br>';
    					}
						echo '<i>You Answered: '.$uAns->$vl.' and the correct answer is: '.$ans->ans.'</i> <br>';
    					echo '<br>';
    				}
    			}
    		}
	}


    public function postrlogin($id)
    {
        $Users = new \App\Models\Users();
        $Scoresheet = new \App\Models\Scoresheet();
        $session = session();
        $incoming = $this->request->getPost();
        $incoming['password'] = $incoming['password'];
        $incoming['clearance'] = 1;

        if (!empty($db = $Users->where($incoming)->find())) {
            $ses_data = [
                'username' => $db[0]['username'],
                'user' => $db[0]['id'],
                'Logged_in' => TRUE,
                'clearance' => $db[0]['clearance']
            ];
            $session->set($ses_data);
            return redirect()->to(base_url('/solution/'.$id));
        } else {
            $this->message('You are yet to take a quiz');
        }
    }

	public function postlogin()
	{
		$Users = new \App\Models\Users();
		$Scoresheet = new \App\Models\Scoresheet();
		$session = session();
		$incoming = $this->request->getPost();
		$incoming['password'] = $incoming['password'];
		$incoming['clearance'] = 1;
		if (!empty($db = $Users->where($incoming)->find())) {
			if ($res = $Scoresheet->where(['user' => $db[0]['id'], 'quiz' => $session->quiz])->find()) {
				$this->message('A score has been recorded for this user on this particular test');
			} else {
				$ses_data = [
					'username' => $db[0]['username'],
					'user' => $db[0]['id'],
					'Logged_in' => TRUE,
					'clearance' => $db[0]['clearance']
				];
				$session->set($ses_data);
				return redirect()->to(base_url('/questions'));
			}
		} else {
			try {
				$db_id = $Users->insert($incoming);
				$db = $Users->where('id', $db_id)->find();
				$ses_data = [
					'username' => $db[0]['username'],
					'user' => $db[0]['id'],
					'Logged_in' => TRUE,
					'clearance' => $db[0]['clearance']
				];
				$session->set($ses_data);
				return redirect()->to(base_url('/questions'));
			} catch (\Exception $e) {
				$this->message('Email has been used before with a different phone number');
			}
		}
	}

    public function logout()
    {
        $session = session();
        $session->destroy();
        // return redirect()->to(base_url());
    }

	//--------------------------------------------------------------------

}
