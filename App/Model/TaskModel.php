<?php 

class TaskModel{
    private $task_id;
    private $nif;
    private $contact_name;
    private $contact_phone;
    private $description;
    private $contact_email;
    private $address;
    private $city;
    private $postal_code;
    private $province_code;
    private $status;
    private $created_at;
    private $assigned_worker;
    private $completion_date;
    private $pre_notes;
    private $post_notes;
    private $sumary_file;

    public function __construct($data) {
        $this->task_id = $data['task_id'];
        $this->nif = $data['nif'];
        $this->contact_name = $data['contact_name'];
        $this->contact_phone = $data['contact_phone'];
        $this->description = $data['description'];
        $this->contact_email = $data['contact_email'];
        $this->address = $data['address'];
        $this->city = $data['city'];
        $this->postal_code = $data['postal_code'];
        $this->province_code = $data['province_code'];
        $this->status = $data['status'];
        $this->created_at = $data['created_at'];
        $this->assigned_worker = $data['assigned_worker'];
        $this->completion_date = $data['completion_date'];
        $this->pre_notes = $data['pre_notes'];
        $this->post_notes = $data['post_notes'];
        $this->sumary_file = $data['sumary_file'];
    }



    // Métodos getters para acceder a los datos de la tarea
    public function getTaskId() {
        return $this->task_id;
    }

    public function getNif() {
        return $this->nif;
    }

    public function getContactName() {
        return $this->contact_name;
    }

    public function getContactPhone() {
        return $this->contact_phone;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getContactEmail() {
        return $this->contact_email;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCity() {
        return $this->city;
    }

    public function getPostalCode() {
        return $this->postal_code;
    }

    public function getProvinceCode() {
        return $this->province_code;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getAssignedWorker() {
        return $this->assigned_worker;
    }

    public function getCompletionDate() {
        return $this->completion_date;
    }

    public function getPreNotes() {
        return $this->pre_notes;
    }

    public function getPostNotes() {
        return $this->post_notes;
    }

    public function getSumaryFile() {
        return $this->sumary_file;
    }

    // Métodos setters para modificar los datos de la tarea si es necesario
    public function setNif($nif) {
        $this->nif = $nif;
    }

    public function setContactName($contact_name) {
        $this->contact_name = $contact_name;
    }

    public function setContactPhone($contact_phone) {
        $this->contact_phone = $contact_phone;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setContactEmail($contact_email) {
        $this->contact_email = $contact_email;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setPostalCode($postal_code) {
        $this->postal_code = $postal_code;
    }

    public function setProvinceCode($province_code) {
        $this->province_code = $province_code;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setAssignedWorker($assigned_worker) {
        $this->assigned_worker = $assigned_worker;
    }

    public function setCompletionDate($completion_date) {
        $this->completion_date = $completion_date;
    }

    public function setPreNotes($pre_notes) {
        $this->pre_notes = $pre_notes;
    }

    public function setPostNotes($post_notes) {
        $this->post_notes = $post_notes;
    }

    public function setSumaryFile($sumary_file) {
        $this->sumary_file = $sumary_file;
    }
}