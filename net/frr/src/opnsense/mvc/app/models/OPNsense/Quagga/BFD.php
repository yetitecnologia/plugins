<?php

namespace OPNsense\Quagga;

use OPNsense\Base\BaseModel;
use OPNsense\Base\Messages\Message;

/*
    Copyright (C) 2024 Deciso B.V.
    Copyright (C) 2017 Fabian Franz
    Copyright (C) 2017 - 2021 Michael Muenz <m.muenz@gmail.com>
    All rights reserved.
    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:
    1. Redistributions of source code must retain the above copyright notice,
       this list of conditions and the following disclaimer.
    2. Redistributions in binary form must reproduce the above copyright
       notice, this list of conditions and the following disclaimer in the
       documentation and/or other materials provided with the distribution.
    THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
    AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
    AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
    OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.
*/
class BFD extends BaseModel
{
       /**
     * {@inheritdoc}
     */
    public function performValidation($validateFullModel = false)
    {
        $messages = parent::performValidation($validateFullModel);
        foreach ($this->neighbors->neighbor->iterateItems() as $neighbor) {
            if (!$validateFullModel && !$neighbor->isFieldChanged()) {
                continue;
            }
            $key = $neighbor->__reference;
            $address_proto = str_contains($neighbor->address, ':') ? 'inet6' : 'inet';
            if (!empty((string)$neighbor->multihop) && $address_proto == 'inet6') {
                $messages->appendMessage(
                    new Message(gettext("Multihop is currently only supported for IPv4"), $key . ".multihop")
                );
            }
        }
        return $messages;
    }
}
