<?php

class MgEventDatesValidator
{
    protected static
        $charset = 'UTF-8',
        $globalDefaultMessages = array('invalid' => 'Invalid.', 'required' => 'Required.');

    protected
        $validatorSchema = null,
        $errorSchema     = null,
        $messages        = array(),
        $options         = array();

    /**
     * Constructor.
     *
     * @param array $options   An array of options
     * @param array $messages  An array of error messages
     */
    public function __construct($options = array(), $messages = array())
    {
        $usedOptions = array();
        if (is_array($options) && count($options)) {
            foreach ($options as $field => $validators) {
                $this->validatorSchema[$field] = $validators;
                foreach ($validators as $option => $constraint) {
                    $usedOptions[] = $option;
                }
            }
        }

        $this->options  = array_merge(array('required' => true), $this->options);
        $this->messages = array_merge(array('required' => self::$globalDefaultMessages['required'], 'invalid' => self::$globalDefaultMessages['invalid']), $this->messages);

        $this->configure();

        // check option names
        if ($diff = array_diff(array_values(array_unique($usedOptions)), array_keys($this->options)))
        {
            throw new InvalidArgumentException(sprintf('%s does not support the following options: \'%s\'.', get_class($this), implode('\', \'', $diff)));
        }

        // check function names
        $methods = array_map(create_function('$v', 'return "validate".str_replace(" ", "", ucwords(strtolower(str_replace("_", " ", $v))));'), array_keys($this->options));
        $nonExists = array();
        foreach ($methods as $method) {
            if (!method_exists(get_class($this), $method)) {
                $nonExists[] = $method;
            }
        }
        if (count($nonExists)) {
            throw new InvalidArgumentException(sprintf('%s does not support the following functions: \'%s\'.', get_class($this), implode('\', \'', $nonExists)));
        }

        // check error code names
        if ($diff = array_diff(array_keys($messages), array_keys($this->messages)))
        {
            throw new InvalidArgumentException(sprintf('%s does not support the following error codes: \'%s\'.', get_class($this), implode('\', \'', $diff)));
        }
    }

    /**
     * Returns an error message given an error code.
     *
     * @param  string $name  The error code
     *
     * @return string The error message, or the empty string if the error code does not exist
     */
    public function getMessage($name)
    {
        return isset($this->messages[$name]) ? $this->messages[$name] : '';
    }

    /**
     * Changes an error message given the error code.
     *
     * @param string $name   The error code
     * @param string $value  The error message
     *
     * @return sfValidatorBase The current validator instance
     */
    public function setMessage($name, $value)
    {
        if (!in_array($name, array_keys($this->messages)))
        {
            throw new InvalidArgumentException(sprintf('%s does not support the following error code: \'%s\'.', get_class($this), $name));
        }

        $this->messages[$name] = $value;

        return $this;
    }

    /**
     * Adds a new error code with a default error message.
     *
     * @param string $name   The error code
     * @param string $value  The error message
     *
     * @return sfValidatorBase The current validator instance
     */
    public function addMessage($name, $value)
    {
        $this->messages[$name] = isset(self::$globalDefaultMessages[$name]) ? self::$globalDefaultMessages[$name] : $value;

        return $this;
    }

    /**
     * Gets an option value.
     *
     * @param  string $name  The option name
     *
     * @return mixed  The option value
     */
    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    /**
     * Changes an option value.
     *
     * @param string $name   The option name
     * @param mixed  $value  The value
     *
     * @return sfValidatorBase The current validator instance
     */
    public function setOption($name, $value)
    {
        if (!in_array($name, array_keys($this->options)))
        {
            throw new InvalidArgumentException(sprintf('%s does not support the following option: \'%s\'.', get_class($this), $name));
        }

        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Adds a new option value with a default value.
     *
     * @param string $name   The option name
     * @param mixed  $value  The default value
     *
     * @return sfValidatorBase The current validator instance
     */
    public function addOption($name, $value = null)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Configures the current validator.
     */
    protected function configure()
    {
        $this->addMessage('max_length', '"%value%" is too long (%max_length% characters max).');
        $this->addMessage('min_length', '"%value%" is too short (%min_length% characters min).');

        $this->addOption('max_length');
        $this->addOption('min_length');

        $this->addMessage('bad_format', '"%value%" does not match the date format.');

        $this->addOption('date_format');

        $this->setOption('required', false);
    }

    public function isValid()
    {
        if (count($this->errorSchema)) {
            $_SESSION[get_class($this)]['errorSchema'] = $this->errorSchema;
            return false;
        } else {
            unset($_SESSION[get_class($this)]);
            return true;
        }
    }

    public function bind($values)
    {
        if (is_array($this->validatorSchema) && count($this->validatorSchema)) {
            foreach ($this->validatorSchema as $field => $validators) {
                foreach ($validators as $validator => $constraint) {
                    $method = 'validate'.str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $validator))));
                    $this->$method($values, $field, $constraint);
                }
            }
        }
    }

    public function validateRequired($values, $field, $constraint)
    {
        if ($constraint) {
            if (!isset($values[$field]) || !trim($values[$field])) {
                $this->errorSchema[$field][] = strtr($this->messages['required'], array());
            }
        }
    }

    public function validateMaxLength($values, $field, $constraint)
    {
        if (isset($values[$field]) && $values[$field]) {
            $clean = (string) trim($values[$field]);

            $length = function_exists('mb_strlen') ? mb_strlen($clean, self::$charset) : strlen($clean);

            if ($length > (int) $constraint) {
                $this->errorSchema[$field][] = strtr($this->messages['max_length'], array('%value%' => $values[$field], '%max_length%' => $constraint));
            }
        }
    }

    public function validateMinLength($values, $field, $constraint)
    {
        if (isset($values[$field]) && $values[$field]) {
            $clean = (string) trim($values[$field]);

            $length = function_exists('mb_strlen') ? mb_strlen($clean, self::$charset) : strlen($clean);

            if ($length < (int) $constraint) {
                $this->errorSchema[$field][] = strtr($this->messages['min_length'], array('%value%' => $values[$field], '%min_length%' => $constraint));
            }
        }
    }

    public function validateDateFormat($values, $field, $constraint)
    {
        if (isset($values[$field]) && $values[$field]) {
            $clean = (string) trim($values[$field]);

            if (!preg_match($constraint, $clean))
            {
                $this->errorSchema[$field][] = strtr($this->messages['bad_format'], array('%value%' => $values[$field]));
            }
        }
    }
}