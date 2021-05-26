<?php


namespace Catcoderphp\CustomConfigProvider\Mapper;


class Mapper
{
    private $error = false;
    private $messages = [];
    private $requestParams = [];
    private static $URLREGEX = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    private static $EMAILREGEX = "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
    private static $MAXPASSREGEX = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!*?&])[A-Za-z\d@$!*?&]{%d,%d}$/";
    private static $LOWPASSREGEX = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{%d,%d}$/";

    /**
     * @return array
     */
    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

    /**
     * @param array $requestParams
     */
    public function setRequestParams(array $requestParams): void
    {
        $this->requestParams = $requestParams;
    }

    public function validator($fields, $data): bool
    {
        $messages = [];
        $this->passwordVerification($data["password"],$data["confirm_password"]);
        foreach ($fields as $key => $field) {
            if (!isset($data[$key])) {
                $this->messages[$key][] = "$key is missing!";
                $this->setError(true);
            } else {
                if ($field["type"] == "password") {
                    $this->validatorByField(
                        $key,
                        $data[$key],
                        $field["type"],
                        $field["password_configuration"],
                        $field["required"]
                    );
                } else {
                    $this->validatorByField($key, $data[$key], $field["type"],[],$field["required"]);
                }
            }
        }
        return $this->isError();
    }

    private function validatorByField (
        $fieldName,
        $fieldValue,
        $type,
        $additionalConfiguration = [],
        $required = false
    ): void
    {
        $types = preg_split('/\|/', $type);
        foreach ($types as $typeOfValidation) {
            switch ($typeOfValidation) {
                case "password":
                    if (!empty($additionalConfiguration)) {
                        $config = $this->getPasswordConfiguration(
                            $additionalConfiguration["min"],
                            $additionalConfiguration["max"],
                            $additionalConfiguration["special_chars"]
                        );
                    } else {
                        $config = $this->getPasswordConfiguration(6,15);
                    }
                    if (!preg_match($config["regex"],$fieldValue)) {
                        $this->messages[$fieldName][] = $config["message"];
                        $this->setError(true);
                    }
                    break;
                case "email" :
                    if (!preg_match(self::$EMAILREGEX,$fieldValue))
                    {
                        $this->messages[$fieldName][] = "$fieldValue not appears like a valid email";
                        $this->setError(true);
                    }
                    break;
                case "url" :
                    if (!preg_match(
                        self::$URLREGEX,
                        $fieldValue))
                    {
                        $this->messages[$fieldName][] = "$fieldValue no is not a valid url";
                        $this->setError(true);
                    }
                    break;
                case "base64":
                    if (!base64_encode(base64_decode($fieldValue, true)) === $fieldValue) {
                        $this->setError(true);
                        $this->messages[$fieldName][] = "$fieldValue is not valid";
                    }
                    break;
                case "string":
                    if (!is_string($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName should be string type";
                        $this->setError(true);
                    }
                    if ($required && empty($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName can't be empty";
                        $this->setError(true);
                    }
                    break;
                case "integer" | "int":
                    if (!is_integer($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName should be integer type";
                        $this->setError(true);
                    }
                    if ($required && is_null($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName can't be empty";
                        $this->setError(true);
                    }
                    break;
                case "boolean":
                    if (!is_bool($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName should be boolean type";
                        $this->setError(true);
                    }
                    break;
                case "arrayOfInt":
                    if (!is_array($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName should be array type";
                        $this->setError(true);
                    } else {
                        foreach ($fieldValue as $key => $itemOnArray) {
                            if (($required) && is_null($itemOnArray)) {
                                $this->messages[$fieldName][] = "The index $key on the array $fieldName can't be empty";
                                $this->setError(true);
                            }
                            if (!is_int($itemOnArray)) {
                                $this->messages[$fieldName][] = "The index $key on the array $fieldName should be integer type";
                                $this->setError(true);
                            }
                        }
                    }
                    break;
                case "arrayOfString":
                    if (!is_array($fieldValue)) {
                        $this->messages[$fieldName][] = "$fieldName should be array type";
                        $this->setError(true);
                    } else {
                        foreach ($fieldValue as $key => $itemOnArray) {
                            if (($required) && empty($itemOnArray)) {
                                $this->messages[$fieldName][] = "The index $key on the array $fieldName can't be empty";
                                $this->setError(true);
                            }
                            if (!is_string($itemOnArray)) {
                                $this->messages[$fieldName][] = "The index $key on the array $fieldName should be string type";
                                $this->setError(true);
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }
    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * @param bool $error
     */
    public function setError(bool $error): void
    {
        $this->error = $error;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    public function getPasswordConfiguration($min_length = 8,$max_length = 10,$specialChars = false)
    {
        $config = [];

        if ($specialChars) {
            $config = [
                "regex" => sprintf(self::$MAXPASSREGEX,$min_length,$max_length),
                "message" => "Your password should be minimum $min_length and maximum $max_length characters, at least one uppercase letter, one lowercase letter, one number and one special character like (@$!*?&)"
            ];
        } else {
            $config = [
                "regex" => sprintf(self::$LOWPASSREGEX,$min_length,$max_length),
                "message" => "Minimum $min_length characters, Max $max_length characters, at least one letter and one number"
            ];
        }
        return $config;
    }

    public function passwordVerification($pass,$confirmPass){

        if ($pass != $confirmPass)
        {
            $this->messages["confirm_password"][] = "Password fields not match";
            $this->setError(true);
        }
    }
}