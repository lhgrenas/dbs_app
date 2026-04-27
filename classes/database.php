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
    function insertUser($username, $user_password_hash, $is_active) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO users (username, user_password_hash, is_active) VALUES (?, ?, ?)');
            $stmt->execute([$username, $user_password_hash, $is_active]);
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
    function insertBorrowers($borrower_firstname, $borrower_lastname, $borrower_email, $borrower_phone_number, $borrower_member_since, $is_active) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borrowers (borrower_firstname, borrower_lastname, borrower_email, borrower_phone_number, borrower_member_since, is_active) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$borrower_firstname, $borrower_lastname, $borrower_email, $borrower_phone_number, $borrower_member_since, $is_active]);
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
    function insertBorroweruser($borrower_id, $user_id) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borroweruser (borrower_id, user_id) VALUES (?, ?)');
            $stmt->execute([$borrower_id, $user_id]);
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

    function insertBorrowerAddress($borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $ba_country, $is_primary) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borroweraddress (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, ba_country, is_primary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $ba_country, $is_primary]);
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
            borrowers.borrower_id,
            borrowers.borrower_firstname,
            borrowers.borrower_lastname,
            borrowers.borrower_email,
            borrowers.is_active AS borrower_active,
            users.user_id,
            users.is_active AS user_active
        FROM borrowers
        LEFT JOIN borroweruser ON borrowers.borrower_id = borroweruser.borrower_id
        LEFT JOIN users ON borroweruser.User_id = users.user_id
        ")->fetchAll();
    }

    function resetUserPassword($userId, $newPassword) {
        $con = $this->opencon();
        $user_password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $con->prepare('UPDATE users SET user_password_hash = ? WHERE user_id = ?');
        return $stmt->execute([$user_password_hash, $userId]);
    }




    //For Books
    function insertBook($book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher]);
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

    function insertBookCopy($book_id, $bc_status) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO bookcopy (book_id, bc_status) VALUES (?, ?)');
            $stmt->execute([$book_id, $bc_status]);
            $copy_id = $con->lastInsertId();
            $con->commit();
            return $copy_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
            }
    }

    function insertBookAuthors($book_id, $author_id) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO bookauthors (book_id, author_id) VALUES (?, ?)');
            $stmt->execute([$book_id, $author_id]);
            $baba_id = $con->lastInsertId();
            $con->commit();
            return $baba_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
            }
    }

    function insertBookGenre($genre_id, $book_id) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO bookgenre (genre_id, book_id) VALUES (?, ?)');
            $stmt->execute([$genre_id, $book_id]);
            $gb_id = $con->lastInsertId();
            $con->commit();
            return $gb_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
            }
    }

    function insertAuthors($author_firstname, $author_lastname, $author_birth_year, $author_nationality){
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO authors(author_firstname, author_lastname, author_birth_year, author_nationality) VALUES (?, ?, ?, ?)');
            $stmt->execute([$author_firstname, $author_lastname, $author_birth_year, $author_nationality]);
            $author_id = $con->lastInsertId();
            $con->commit();
            return $author_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function insertGenre($genre_name){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO genres(genre_name) VALUES(?)');
            $stmt->execute([$genre_name]);
            $genre_id = $con->lastInsertId();
            $con->commit();
            return $genre_id;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    //Authors
    function viewAuthors() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM authors")->fetchAll();
    }

    function viewGenre() {
        $con = $this->opencon();
        return $con->query("SELECT * FROM genres ORDER BY 1")->fetchAll();
    }

    function viewBooks() {
        $con = $this->opencon();
        return $con->query("SELECT 
        books.book_id, 
        books.book_title, 
        books.book_isbn, 
        books.book_publication_year, 
        books.book_edition, 
        books.book_publisher, 
        COUNT(bookcopy.copy_id) AS copies, 
        SUM(bookcopy.bc_status = 'AVAILABLE') AS available_copies 
        FROM books 
        LEFT JOIN bookcopy ON books.book_id = bookcopy.book_id 
        GROUP BY books.book_id")->fetchAll();
    }
    function  viewLoans() {
    $con = $this->opencon();
    $result = $con->query("SELECT 
        loan.loan_id, 
        CONCAT(borrowers.borrower_firstname, ' ', borrowers.borrower_lastname) AS Borrower, 
        loan.loan_status AS loan_status, 
        loan.loan_date, 
        users.username AS processed_by_user 
        FROM loan 
        JOIN borrowers ON loan.borrower_id = borrowers.borrower_id 
        JOIN users ON loan.processed_by_user_id = users.user_id
        ORDER BY loan.loan_date DESC 
    ");

    return $result ? $result->fetchAll() : [];
    }

    function updateBook($book_id, $book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
            $stmt = $con->prepare('UPDATE books SET book_title = ?, book_isbn = ?, book_publication_year = ?, book_edition = ?, book_publisher = ? WHERE book_id = ?');
            $stmt->execute([$book_title, $book_isbn, $book_publication_year, $book_edition, $book_publisher, $book_id]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }
    function countBook(){
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_books FROM Books")->fetchColumn();
    }

    function countCopy(){
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_copies FROM Bookcopy")->fetchColumn();
    }

}


    //For Admin Dashboard
        
?>