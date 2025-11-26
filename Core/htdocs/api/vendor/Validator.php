<?php

class Validator
{
    protected $data;
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(array $rules)
    {
        foreach($rules AS $field => $ruleListString)
        {
            $ruleListArray = explode(',', $ruleListString);
            
            $required = in_array('required', $ruleListArray);

            foreach($ruleListArray AS $rule)
            {
                if($rule === 'required' && !array_key_exists($field, $this->data))
                {
                    $this->errors[] = "{$field} is required.";
                }
                if($rule === 'number' && array_key_exists($field, $this->data))
                {
                    if($this->data[$field] != "" && $required)
                    {
                        if(!is_numeric( $this->data[$field] ))
                        {
                            $this->errors[] = "{$field} must be a number.";    
                        }
                    }
                }
            }
        }

        return count($this->errors) == 0;
    }

    public function errors()
    {
        return $this->errors;
    }
}