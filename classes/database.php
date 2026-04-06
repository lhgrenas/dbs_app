<?php 

class database {
    function opencon(): PDO {
        return new PDO(
        dsn: 'mysql:host=localhost;
        dbname=dbs_app',
        username: 'root',
        password: '');
    }

    function insertUser($email, $user_password_hash, $is_active) {
        $con = $this-> opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username,user_password_hash,is_active) 
            VALUES(?,?,?)');
            $stmt->execute([$email, $user_password_hash, $is_active]);
            $user_id = $con->lastInsertId();
            $con->commit();
            return $user_id;

        } catch(PDOException $e){
            if($con->inTransaction()) { 
                $con->rollBack();
            }
            throw $e;
        }
    }

    function insertBorrower($firstname, $lastname, $email, $phone, $member_since, $is_active){
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borrowers (borrower_firstname,borrower_lastname,borrower_email,borrower_phone_number,borrower_member_since,is_active) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_active]);
            $borrower_id = $con->lastInsertId();
            $con->commit();
            return $borrower_id;

        }catch(PDOException $e){
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function insertBorrowerUser($borrower_id, $user_id){
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO BorrowerUser (borrower_id,user_id) VALUES (?,?)');
            $stmt->execute([$borrower_id, $user_id]);
            $con->commit();


        }catch(PDOException $e){
            if($con->inTransaction()) {
                $con->rollBack();
        }
        throw $e;
        }
    
    }
}

?>



