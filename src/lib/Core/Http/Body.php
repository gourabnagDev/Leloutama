<?php
/**
 * Created by PhpStorm.
 * User: gourab
 * Date: 14/5/16
 * Time: 9:12 AM
 */

namespace Leloutama\lib\Core\Http;
class Body {
    /**
     * @var string
     * Stores the raw request body.
     */
    protected $rawRequestBody = null;
    /**
     * @var array
     * Stores the parsed k-v pairs of the request.
     */
    protected $parsedBody = [];

    /**
     * Loads the raw request body onto the current instance.
     * If the body is empty it return @bool false.
     * Otherwise @bool true.
     * @param string $rawRequestBody
     * @return bool
     */
    public function load(string $rawRequestBody): bool {
        if(strlen(trim($rawRequestBody)) === 0) {
            return false;
        }

        $this->rawRequestBody = $rawRequestBody;
        return true;
    }

    /**
     * Parses the raw request body stored in $this->rawRequestBody.
     * Returns an array containing the k-v pairs.
     * If the raw request body is empty, it returns an empty array.
     * @param string $fieldsDelimiter
     * @param string $rawBody
     * @return array
     */
    public function parse(string $rawBody = "", string $fieldsDelimiter = ";"): array {
        /* Check if the user wants to parse the stored string, or any other thing */
        if(strlen(trim($rawBody)) === 0) {
            if(is_null($this->rawRequestBody)) {
                $this->parsedBody = [];
                return [];
            }
            /* Make a local copy of raw body from THIS object. */
            $rawBody = $this->rawRequestBody;
        }

        /* Variable to store the k-v pairs. */
        $values = [];

        /* Delimiter of separate fields are ';'. */
        $fields = explode($fieldsDelimiter, $rawBody);

        /* Get the number of fields. */
        $fieldsCount = count($fields);

        /* Need to loop over all the fields, to separate them into key value pairs. */
        for($i = 0; $i < $fieldsCount; $i++) {
            /* Get the current field. */
            $currentField = $fields[$i];

            /* Delimiter for k-v pairs is '='. */
            $kvPair = explode("=", $currentField);

            /* Insert this data into the main store. */
            $values[$kvPair[0]] = urldecode($kvPair[1]);
        }

        $this->parsedBody = $values;

        return $values;
    }

    /**
     * Returns the parsed request body.
     * @return array
     */
    public function getParsedBody(): array {
        return $this->parsedBody;
    }

    /**
     * Returns the raw request body.
     * @return string
     */
    public function getRawBody() {
        return $this->rawRequestBody;
    }
}