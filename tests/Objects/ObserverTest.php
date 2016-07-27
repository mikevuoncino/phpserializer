<?php

use MVuoncino\Helper\AbstractToken;
use MVuoncino\Helper\StringToken;
use MVuoncino\Contracts\ConfabulatorInterface;

class ObserverTest implements \SplObserver
{
    public function update(SplSubject $subject)
    {
        $token = $subject->getLastToken();
        if ($token->getType() == 's') {
            $token = new StringToken('vuoncino');
            $subject->setLastToken($token);
        }
    }
}