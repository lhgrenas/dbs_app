<?php
 
class database {
    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;
            dbname=dbs_app',
            'root',
            ''
        );
    }
    function insertUser($Username, $User_password_hash, $Is_active) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO users (Username, User_password_hash, Is_active) VALUES (?, ?, ?)');
            $stmt->execute([$Username, $User_password_hash, $Is_active]);
            $user_id = $con->lastInsertId();
            $con->commit();
            return $user_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }
    function insertBorrowers($Borrower_firstname, $Borrower_lastname, $Borrower_email, $Borrower_phone_number, $Borrower_member_since, $Is_active) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borrowers (Borrower_firstname, Borrower_lastname, Borrower_email, Borrower_phone_number, Borrower_member_since, Is_active) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$Borrower_firstname, $Borrower_lastname, $Borrower_email, $Borrower_phone_number, $Borrower_member_since, $Is_active]);
            $borrower_id = $con->lastInsertId();
            $con->commit();
            return $borrower_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }
    function insertBorroweruser($Borrower_ID, $User_ID) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borroweruser (Borrower_ID, User_ID) VALUES (?, ?)');
            $stmt->execute([$Borrower_ID, $User_ID]);
            $bu_id = $con->lastInsertId();
            $con->commit();
            return $bu_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

        function insertBorrowerAddress($Borrower_ID, $Ba_house_number, $Ba_street, $Ba_barangay, $Ba_city, $Ba_province, $Ba_postal_code, $Ba_country, $Is_primary) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borroweraddress (Borrower_ID, Ba_house_number, Ba_street, Ba_barangay, Ba_city, Ba_province, Ba_postal_code, Ba_country, Is_primary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$Borrower_ID, $Ba_house_number, $Ba_street, $Ba_barangay, $Ba_city, $Ba_province, $Ba_postal_code, $Ba_country, $Is_primary]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function viewBorroweruser() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM borrowers")->fetchAll();
    }

    function viewBorrowers() {
        $con = $this->opencon();
        return $con->query("SELECT 
            borrowers.Borrower_ID,
            borrowers.Borrower_firstname,
            borrowers.Borrower_lastname,
            borrowers.Borrower_email,
            borrowers.Is_active AS Borrower_active,
            users.User_ID,
            users.Is_active AS User_active
        FROM borrowers
        LEFT JOIN borroweruser ON borrowers.Borrower_ID = borroweruser.Borrower_ID
        LEFT JOIN users ON borroweruser.User_ID = users.User_ID
        ")->fetchAll();
    }

        function resetUserPassword($userId, $newPassword) {
        $con = $this->opencon();
        $User_password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $con->prepare('UPDATE users SET User_password_hash = ? WHERE User_ID = ?');
        return $stmt->execute([$User_password_hash, $userId]);
    }




    //For Books
    function insertBook($Book_title, $Book_isbn, $Book_publication_year, $Book_edition, $Book_publisher) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO books (Book_title, Book_isbn, Book_publication_year, Book_edition, Book_publisher) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$Book_title, $Book_isbn, $Book_publication_year, $Book_edition, $Book_publisher]);
            $book_id = $con->lastInsertId();
            $con->commit();
            return $book_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

        function insertBookCopy($Book_ID, $BC_STATUS) {
            $con = $this->opencon();
            try {
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO bookcopy (Book_ID, BC_STATUS) VALUES (?, ?)');
                $stmt->execute([$Book_ID, $BC_STATUS]);
                $copy_ID = $con->lastInsertId();
                $con->commit();
                return $copy_ID;
            } catch (PDOException $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                throw $e;
            }
        }

        function insertBookAuthor($Book_ID, $Author_ID) {
            $con = $this->opencon();
            try {
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO bookauthors (Book_ID, Author_ID) VALUES (?, ?)');
                $stmt->execute([$Book_ID, $Author_ID]);
                $baba_ID = $con->lastInsertId();
                $con->commit();
                return $baba_ID;
            } catch (PDOException $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                throw $e;
            }
        }

        function insertBookGenre($Genre_ID, $Book_ID) {
            $con = $this->opencon();
            try {
                $con->beginTransaction();
                $stmt = $con->prepare('INSERT INTO bookgenre (Genre_ID, Book_ID) VALUES (?, ?)');
                $stmt->execute([$Genre_ID, $Book_ID]);
                $gb_ID = $con->lastInsertId();
                $con->commit();
                return $gb_ID;
            } catch (PDOException $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                throw $e;
            }
        }

        function viewAuthors() {
            $con = $this->opencon();
            return $con->query("SELECT * FROM authors")->fetchAll();
        }

        function viewGenre() {
            $con = $this->opencon();
            return $con->query("SELECT * FROM genres")->fetchAll();
        }

        function viewBooks() {
            $con = $this->opencon();
            return $con->query("SELECT books.Book_ID, books.Book_title, books.Book_isbn, books.Book_publication_year, books.Book_edition, books.Book_publisher, 
        COUNT(bookcopy.Copy_ID) AS Copies, 
        SUM(bookcopy.BC_STATUS = 'AVAILABLE') AS Available_Copies 
        FROM books 
        LEFT JOIN bookcopy ON books.Book_ID = bookcopy.Book_ID 
        GROUP BY books.Book_ID")->fetchAll();
    }




    //For Admin Dashboard
        
}
?>