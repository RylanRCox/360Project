<?php
class functionsTest extends \PHPUnit\Framework\TestCase {
    public function testCheckLogin(){
        $functions = new App\functions;
        $result = $functions->checkLogin();
        $this->assertEquals(1,$result);
    }
}
?>