<?php
/**
 * @link https://www.simplonprod.co
 * @copyright Copyright (c) 2021 Simplon.Prod
 */

namespace simplonprod\newsletter\models;

use Craft;
use craft\base\Model;
use simplonprod\newsletter\Newsletter;

/**
 * NewsletterForm class
 *
 * @author albanjubert
 **/
class NewsletterForm extends Model
{
    public $email;
    public $salutation = "";
    public $firstName;
    public $lastName;
    public $consent;

    private $fields = [];

    public function rules(): array
    {
        return [
            ['firstName', 'trim'],
            ['firstName', 'required', 'message' => Craft::t('newsletter', 'Please provide a first name.')],
            ['lastName', 'trim'],
            ['lastName', 'required', 'message' => Craft::t('newsletter', 'Please provide a last name.')],
            ['email', 'trim'],
            ['email', 'required', 'message' => Craft::t('newsletter', 'Please provide a valid email address.')],
            ['email', 'email', 'message' => Craft::t('newsletter', 'Please provide a valid email address.')],
            ['consent', 'required', 'requiredValue' => true, 'message' => Craft::t('newsletter', 'Please provide your consent.')],
        ];
    }

    public function subscribe(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        // Use newsletter module to register new user
        $newsletterAdapater = Newsletter::$plugin->adapter;

        $this->fields = [
          "FNAME" => $this->firstName,
          "LNAME" => $this->lastName,
          "MMERGE5" => "Liebe/r",
          "MMERGE6" => "na",
        ];

        if (!$newsletterAdapater->subscribe($this->email, $this->fields)) {
            $this->addError('email', $newsletterAdapater->getSubscriptionError());
            return false;
        }
        return true;
    }
}
