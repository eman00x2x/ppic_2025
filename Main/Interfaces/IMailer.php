<?php

namespace EO\Interfaces;

/**
 * Interface IMailer
 */
interface IMailer
{
    /**
     * @param string $email
     * @return $this
     */
    public function sender(string $email);

    /**
     * @param string $subject
     * @return $this
     */
    public function subject(string $subject);

    /**
     * @param array $emails
     * @return $this
     */
    public function to(array $emails);

    /**
     * @param array $emails
     * @return $this
     */
    public function cc(array $emails);

    /**
     * @param array $emails
     * @return $this
     */
    public function bcc(array $emails);

    /**
     * @param array $attachments
     * @return $this
     */
    public function attachments(array $attachments);

    /**
     * @param string $subject
     * @return bool
     */
    public function send(string $subject);

    /**
     * @param string $template
     * @param mixed $message
     * @return $this
     */
    public function template(string $template, $message);
}
