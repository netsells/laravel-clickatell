laravel-clickatell
==================

Send to one number
------------------

    $message = new Clickatell;

    $status = $message->to('00000000000')
                    ->message('Hello world!')
                    ->send();


Send the same message to multiple numbers
-----------------------------------------

    $message = new Clickatell;

    $status = $message->to(array('00000000000', '11111111111', '22222222222'))
                    ->message('Hello world!')
                    ->send();


Responses
---------

**Failed**

    $status = array('status' => 'failed', 'message' => 'Error Message');

**Success**

    $status = array('status' => 'success', 'id' => 'AABBCCDDEEFF');