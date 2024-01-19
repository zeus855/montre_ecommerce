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

     // Méthode pour envoyer un e-mail de prise de contact.
   public function sendEmail(Contact $contact): void
   {
        // Crée une nouvelle instance de la classe TemplatedEmail.
        $email = (new TemplatedEmail())
        // Définit l'expéditeur de l'e-mail en utilisant l'adresse e-mail spécifiée dans les paramètres de l'application ('app.contact')
        ->from($this->parameterBag->get('app.contact'))
        // Définit le destinataire de l'e-mail en utilisant l'adresse e-mail spécifiée dans les paramètres de l'application ('app.contact')
        ->to(new Address($this->parameterBag->get('app.contact')))
        // Définit le sujet de l'e-mail
        ->subject('Prise de contact')
        // Spécifie le template Twig à utiliser
        ->htmlTemplate('contact/email.html.twig')
        // Fournit des données de contexte au template Twig
        ->context([
            'contact' => $contact,
        ]);

        // Utilise le service MailerInterface pour envoyer l'e-mail
        $this->mailer->send($email);
   } 
}