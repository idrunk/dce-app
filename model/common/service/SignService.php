<?php
/**
 * Author: Drunk (drunkce.com;idrunk.net)
 * Date: 2021-03-13 17:33
 */

namespace service;

use model\Member;

class SignService {
    public function signIn(string $nickname): Member|false {
        return Member::find($nickname);
    }

    public function signUp(string $nickname, string $brief): bool {
        $member = new Member();
        $member->nickname = $nickname;
        $member->brief = $brief;
        return $member->save();
    }
}