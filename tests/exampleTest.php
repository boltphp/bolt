<?php

class exampleText extends Test {

    public function testExample() {
        $app = $this->createApp();
        $this->assertInstanceOf('bolt\application', $app);
    }

}