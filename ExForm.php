<?php

namespace Drupal\ex_form\Form;

use Drupal\Core\Form\FormBase;																			
use Drupal\Core\Form\FormStateInterface;													


class ExForm extends FormBase {

	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['firstname'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Firstname'),
			'#description' => $this->t('Имя не должно содержать цифр'),
			'#required' => TRUE,
		];

      $form['lastname'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Lastname'),
			'#description' => $this->t('Фамилия не должна содержать цифр'),
			'#required' => TRUE,
		];

    $form['subject'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Subject'),
			'#required' => TRUE,
		]; 

    $form['message'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Message'),
			'#required' => TRUE,
		];

    $form['email'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Email'),
			'#required' => TRUE,
		];

		$form['actions']['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Submit'),
		];

    	return $form;
	}
   public function validateForm(array &$form, FormStateInterface $form_state) {
		$title = $form_state->getValue('lastname');
    $title = $form_state->getValue('firstname');
		$is_number = preg_match("/[\d]+/", $title, $match);

		if ($is_number > 0) {
			$form_state->setErrorByName('lastname', $this->t('Строка содержит цифру.'));
      $form_state->setErrorByName('firstname', $this->t('Строка содержит цифру.'));
		}
	}


	public function submitForm(array &$form, FormStateInterface $form_state) {
		drupal_set_message($this->t('Thank you @name,your e-mail is @email', array(
    $firstname => $form_state ->getValue('firstname'),
    $lastname => $form_state ->getValue('lastname'),
    $subject => $form_state ->getValue('subject'),
    $message => $form_state ->getValue('message'),
    $email => $form_state ->getValue('email');


    $url = "https://api.hubapi.com/contacts/v1/contact/createOrUpdate/email/".$email."/?hapikey=0425c9e1-339a-495d-9acf-02363b6f9b8c";

    $data = array(
      'properties' => [
        [
          'property' => 'firstname',
          'value' => $firstname
        ],
        [
          'property' => 'lastname',
          'value' => $lastname 
        ],
        [
          'property' => 'subject',
          'value' => $subject
        ],
        [
          'property' => 'message',
          'value' => $message 
        ],
        [
          'property' => 'email',
          'value' => $email
        ],
      ]
    );


    $json = json_encode($data,true);

    $response = \Drupal::httpClient()->post($url.'&_format=hal_json', [
      'headers' => [
        'Content-Type' => 'application/json'
      ],
        'body' => $json
    ]);


	}

}

