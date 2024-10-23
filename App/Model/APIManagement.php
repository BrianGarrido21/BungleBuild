<?php

class APIManagement {

    private $baseUrl;
    private $headers = [];

    // Constructor que inicializa la URL base de la API y permite agregar encabezados si es necesario.
    public function __construct($baseUrl, $headers = []) {
        $this->baseUrl = $baseUrl;
        $this->headers = $headers;
    }

    // Método para realizar peticiones GET a la API
    public function get($endpoint, $params = []) {
        try {
            // Construir la URL completa con los parámetros de la petición GET
            $url = $this->baseUrl . $endpoint;
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }

            // Iniciar cURL y configurar la solicitud
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeaders());

            // Ejecutar la petición
            $response = curl_exec($ch);

            // Verificar si hubo errores en la solicitud
            if (curl_errno($ch)) {
                throw new Exception('Error en la solicitud: ' . curl_error($ch));
            }

            // Verificar el código de estado HTTP
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode >= 400) {
                throw new Exception('Error en la API: Código de estado HTTP ' . $httpCode);
            }

            curl_close($ch);

            // Retornar la respuesta tal cual en formato JSON
            return $response;
        } catch (Exception $e) {
            // Retornar un JSON con el error en caso de fallos
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    // Método para realizar peticiones POST a la API
    public function post($endpoint, $data = []) {
        try {
            // Construir la URL completa
            $url = $this->baseUrl . $endpoint;

            // Iniciar cURL y configurar la solicitud
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeaders());
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            // Ejecutar la petición
            $response = curl_exec($ch);

            // Verificar si hubo errores en la solicitud
            if (curl_errno($ch)) {
                throw new Exception('Error en la solicitud: ' . curl_error($ch));
            }

            // Verificar el código de estado HTTP
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode >= 400) {
                throw new Exception('Error en la API: Código de estado HTTP ' . $httpCode);
            }

            curl_close($ch);

            // Retornar la respuesta tal cual en formato JSON
            return $response;
        } catch (Exception $e) {
            // Retornar un JSON con el error en caso de fallos
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    // Método para configurar los encabezados personalizados
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    // Método privado para construir los encabezados HTTP
    private function buildHeaders() {
        $headersArray = [];
        foreach ($this->headers as $key => $value) {
            $headersArray[] = "$key: $value";
        }
        return $headersArray;
    }

    // Método para parsear la respuesta JSON a un array asociativo
    private function parseToArray($json) {
        return json_decode($json, true);  // Devuelve un array asociativo
    }

    // Método para parsear la respuesta JSON a un objeto
    private function parseToObject($json) {
        return json_decode($json);  // Devuelve un objeto estándar de PHP
    }

    // Método para obtener la respuesta parseada según el tipo especificado (array u objeto)
    public function getParsedResponse($response, $type = 'array') {
        if ($type === 'array') {
            return $this->parseToArray($response);
        } elseif ($type === 'object') {
            return $this->parseToObject($response);
        } else {
            throw new Exception('Invalid type specified for parsing. Use "array" or "object".');
        }
    }
}