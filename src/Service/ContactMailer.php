<?php

namespace App\Service;

use App\Model\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactMailer
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private MailerInterface $mailer
    ) 
    {
    }

   public function sendEmail(Contact $contact): void
   {
        $email = (new TemplatedEmail())
        ->from($this->parameterBag->get('app.contact'))
        ->to(new Address($this->parameterBag->get('app.contact')))
        ->subject('Prise de contact')
        ->htmlTemplate('contact/email.html.twig')
        ->context([
            'contact' => $contact,
        ]);

        $this->mailer->send($email);
   } 
}