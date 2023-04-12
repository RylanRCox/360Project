<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class functionsTest extends TestCase {
   
 
    public function testCheckLogin(){
        ob_start();
        $functions = new App\functions;
        $result = $functions->checkLogin("test","test");
        $this->assertTrue($result);
        $result = $functions->checkLogin("tt","test");
        $this->assertFalse($result);
        ob_end_clean();
    }
    
    public function testGetUser(){
        ob_start();
        $func = new App\functions;
        $rslt = $func->getUser(1);
        $this->assertEquals($rslt,"test");
        $rslt = $func->getUser(-2);
        $this->assertEquals($rslt,NULL);
        $rslt = $func->getUser(1);
        $this->assertNotEquals($rslt,"heyo");
        ob_end_clean();
    }

    public function testGetComment(){
        ob_start();
        $func = new App\functions;
        $rslt = $func->getComment(1);
        $this->assertNotNull($rslt);
        $rslt = $func->getComment(6);
        $this->assertTrue(empty($rslt));
        ob_end_clean();
    }

    
    public function testAddComment(){
        ob_start();
        $func = new App\functions;
        $rslt = $func->addComment(0,"Test content", 3);
        $this->assertTrue($rslt);
        $rslt = $func->addComment(NULL,"Test content", -2);
        $this->assertFalse($rslt);
        ob_end_clean();
      
    }

  
    
    public function testGetFeedSlice(){
        ob_start();
        $func = new App\functions;
        $rslt = $func->getFeedSlice(1, 0);
        $this->assertTrue(!empty($rslt));
        $rslt = $func->getFeedSlice(-1, 0);
        $this->assertTrue(empty($rslt));
        $rslt = $func->getFeedSlice(NULL, 1);
        $this->assertTrue(empty($rslt));
        ob_end_clean();
      
    }
 
   
 public function testGetNotifications(){
    ob_start();
    $functions = new App\functions;
    $result = $functions->getNotifications(1);
    $this->assertTrue($result);
    ob_end_clean();
}

public function testGetPost(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->getPost(1);
    $this->assertTrue(!empty($rslt));
    $rslt = $func->getPost(-20);
    $this->assertTrue(empty($rslt));
    $rslt = $func->getPost(NULL);
    $this->assertTrue(empty($rslt));
    ob_end_clean();
}

public function testSavePost(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->savePost("new post", 'yo', 1, 1);
    $this->assertTrue($rslt);
    $rslt = $func->savePost("new post", 'yo', 2, -20);
    $this->assertFalse($rslt);
    $rslt = $func->savePost("new post", 'yo', -1, -20);
    $this->assertFalse($rslt);
    $rslt = $func->savePost(NULL, 'yo', 1, 20);
    $this->assertFalse($rslt);
    $rslt = $func->savePost('test post', NULL, 1, 20);
    $this->assertFalse($rslt);
    $rslt = $func->savePost('test post', 'test', NULL, 20);
    $this->assertFalse($rslt);
    $rslt = $func->savePost('test post', 'test', 'ahh', NULL);
    $this->assertFalse($rslt);
    ob_end_clean();
}

public function testSaveSignup(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->saveSignUp('testingPHP','test1','testingPHP');
    $this->assertTrue($rslt);
    $rslt = $func->saveSignUp(NULL,'test1','testingPHP');
    $this->assertFalse($rslt);
    $rslt = $func->saveSignUp('yo',NULL,'testingPHP');
    $this->assertFalse($rslt);
    $rslt = $func->saveSignUp('yo','will this end',NULL);
    $this->assertFalse($rslt);
    ob_end_clean();
}

    
public function testUpdateDisplayName(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->updateDisplayName("test");
    $this->assertTrue($rslt);
    $rslt = $func->updateDisplayName(NULL);
    $this->assertFalse($rslt);
    ob_end_clean();
}

public function testUpdateEmail(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->updateEmail("test1");
    $this->assertTrue($rslt);
    $rslt = $func->updateEmail("test1");
    $this->assertFalse($rslt);
    $rslt = $func->updateEmail(NULL);
    $this->assertFalse($rslt);
    $rslt = $func->updateEmail("test");
    $this->assertTrue($rslt);
    ob_end_clean();
}

public function testUpdatePassword(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->updatePassword("test");
    $this->assertTrue($rslt);
    $rslt = $func->updatePassword(NULL);
    $this->assertFalse($rslt);
    ob_end_clean();
}



public function testUpdateUserBio(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->updateUserBio("test");
    $this->assertTrue($rslt);
    $rslt = $func->updateUserBio(NULL);
    $this->assertFalse($rslt);
    ob_end_clean();
}


public function testUpdateUserImage(){
    ob_start();
    $func = new App\functions;
    $rslt = $func->updateUserImage(false);
    $this->assertFalse($rslt);
    ob_end_clean();
}

     /*
    public function testGetHidden(){
        $func = new App\functions;
        $rslt = $func->getHidden(5,1);
       
        $this->assertEquals($rslt,"115511");
        
    }*/

      /*public function testDeleteHidden(){
        $func = new App\functions;
        $rslt = $func->addComment(NULL,"Test content", 3);
        $this->assertTrue($rslt);
        $rslt = $func->addComment(NULL,"Test content", -2);
        $this->assertFalse($rslt);
      
    }*/
   


    
}
?>