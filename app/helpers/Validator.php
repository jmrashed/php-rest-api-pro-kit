<?php

namespace App\Helpers;

class Validator
{
    private $errors = [];
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(array $rules)
    {
        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule => $param) {
                $value = $this->data[$field] ?? null;

                switch ($rule) {
                    case 'required':
                        if (empty($value) && $value !== 0 && $value !== '0') {
                            $this->addError($field, "{$field} is required.");
                        }
                        break;
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, "{$field} must be a valid email address.");
                        }
                        break;
                    case 'min':
                        if (!empty($value) && strlen($value) < $param) {
                            $this->addError($field, "{$field} must be at least {$param} characters long.");
                        }
                        break;
                    case 'max':
                        if (!empty($value) && strlen($value) > $param) {
                            $this->addError($field, "{$field} must not exceed {$param} characters.");
                        }
                        break;
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $this->addError($field, "{$field} must be a number.");
                        }
                        break;
                    case 'confirmed':
                        $confirmField = $field . '_confirmation';
                        if (!empty($value) && ($value !== ($this->data[$confirmField] ?? null))) {
                            $this->addError($field, "{$field} confirmation does not match.");
                        }
                        break;
                    // Add more validation rules as needed
                }
            }
        }
        return empty($this->errors);
    }

    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function errors()
    {
        return $this->errors;
    }
}