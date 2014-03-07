<?php
namespace Nannyster\Validator;

use Phalcon\Validation\Validator;
use Phalcon\Validation\Message;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Mvc\Collection;
use Nannyster\Models\Users;

class UniqueValidator extends Validator implements ValidatorInterface
{
    public function validate($record, $attribute)
    {
        if (Users::count(array(array($attribute => $record->getValue($attribute)))) > 0) {
        	$record->appendMessage(new Message($this->getOption('message')));
            return false;
        }
        return true;
    }
}