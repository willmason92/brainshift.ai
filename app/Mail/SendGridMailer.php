<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Sichikawa\LaravelSendgridDriver\SendGrid;

class SendGridMailer extends Mailable
{
    use Queueable, SerializesModels, SendGrid;

    private $_tokens = [];

    private $_templateId = '';

    /**
     * Create a new message instance pass in variables.
     *
     * @return void
     */
    public function __construct(string $templateId, array $tokens)
    {
        $this->_templateId = $templateId;
        foreach ($tokens as $key => $value) {
            //if key is alpha and value is a string
            if (preg_match('/^[\w-]+$/', $key) && strval($value) == $value) {
                $this->_tokens[strtolower($key)] = strval($value);
            }
        }
    }

    public function build()
    {
        if (! empty($this->_templateId)) {
            $params = [
                'template_id' => $this->_templateId,
            ];

            if (! empty($this->_tokens)) {
                $params['personalizations'] =
                [
                    [
                        'dynamic_template_data' => $this->_tokens,
                    ],
                ];
            }

            //todo load from settings
            return $this
                ->view([])
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->sendgrid($params);
        } else {
            throw new \ErrorException('SendGrid Template ID not set.');
        }
    }
}
