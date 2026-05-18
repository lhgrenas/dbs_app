<?php
 
class database {
    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;
            dbname=abs_app',
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

    function createLoanWithItems($borrower_id, $processed_by_user_id, $copy_ids, $li_duedate, $condition_out){
        $con = $this->opencon();
        
        try{
            $con->beginTransaction();
            $insertLoanStmt = $con->prepare("INSERT INTO Loan (borrower_id, processed_by_user_id, loan_status, loan_date) VALUES (?, ?, 'OPEN', NOW())");
            $insertLoanStmt->execute([$borrower_id, $processed_by_user_id]);
            $loan_id = $con->lastInsertId();

            $checkCopyStmt = $con->prepare("SELECT bc_status FROM BookCopy WHERE copy_id = ?");
            $activeLoanStmt = $con->prepare("SELECT COUNT(*) as active_count FROM LoanItem JOIN Loan ON LoanItem.loan_id = Loan.loan_id WHERE LoanItem.copy_id = ? AND LoanItem.li_returned_at IS NULL AND Loan.loan_status = 'OPEN'");
        
            $insertLoanItemStmt = $con->prepare("INSERT INTO loanitem(loan_id, copy_id, li_duedate, condition_out) VALUES (?,?,?,?)");
            $updateCopyStmt = $con->prepare("UPDATE bookcopy SET bc_status = 'ON_LOAN' WHERE copy_id = ?");
            
            foreach ($copy_ids as $copy_id) {

            $checkCopyStmt->execute([$copy_id]);
            $copyStatus = $checkCopyStmt->fetch();

            if (!$copyStatus) {
                throw new Exception("Copy ID $copy_id does not exist.");
            }

            if ($copyStatus['bc_status'] !== 'AVAILABLE') {
                throw new Exception("Copy ID $copy_id is not available.");
            }

            $activeLoanStmt->execute([$copy_id]);
            $activeLoan = $activeLoanStmt->fetch();

            if ($activeLoan['active_count'] > 0) {
                throw new Exception("Copy already on active loan.");
            }

            $insertLoanItemStmt->execute([$loan_id, $copy_id, $li_duedate, $condition_out]);
            $updateCopyStmt->execute([$copy_id]);
            
            } $con->commit();
              return $loan_id;
            
        }catch(Exception $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function processLoanReturn($loan_item_id, $li_returned_at, $condition_in){
        $con = $this->opencon();

        try {
        $con->beginTransaction();
        $getLoanItemStmt=$con->prepare("SELECT copy_id, loan_id FROM loanitem WHERE loan_item_id = ?");
        $getLoanItemStmt->execute([$loan_item_id]);
    
        $loanItem = $getLoanItemStmt->fetch();
        if(!$loanItem){
            throw new Exception("Loan Item ID $loan_item_id does not exist.");
        }

        $copy_id = $loanItem["copy_id"];
        $loan_id = $loanItem["loan_id"];
        
        $updateReturnDateStmt = $con->prepare("UPDATE loanitem SET li_returned_at = ?, condition_in = ? WHERE loan_item_id = ?");
        $updateReturnDateStmt->execute([$li_returned_at,$condition_in, $loan_item_id]);

        $updateBookCopy = $con->prepare("UPDATE bookcopy SET bc_status = 'AVAILABLE' WHERE copy_id = ?");
        $updateBookCopy->execute([$copy_id]);


        $checkLoanItems = $con->prepare("SELECT COUNT(*) AS unreturned_count FROM loanitem WHERE loan_id = ? AND li_returned_at IS NULL");

     
        $checkLoanItems->execute([$loan_id]);   
        $result= $checkLoanItems->fetch();

     

        if($result['unreturned_count'] == 0) {
            $updateLoan = $con->prepare("UPDATE loan SET loan_status = 'Closed' WHERE loan_id = ?");
            $updateLoan->execute([$loan_id]);
        }

        $con->commit();
        return true;

        }catch(Exception $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
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
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function updateAuthor($author_id, $author_firstname, $author_lastname, $author_birth_year, $author_nationality){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('UPDATE authors SET author_firstname = ?, author_lastname = ?, author_birth_year = ?, author_nationality = ? WHERE author_id = ?');
            $stmt->execute([$author_firstname, $author_lastname, $author_birth_year, $author_nationality, $author_id]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function updateGenre($genre_id, $genre_name){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('UPDATE genres SET genre_name = ? WHERE genre_id = ?');
            $stmt->execute([$genre_name, $genre_id]);
            $con->commit();
            return true;
        } catch (PDOException $e) {
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function countBook(){
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_books FROM books")->fetchColumn();
    }

    function countCopy(){
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS total_copies FROM bookcopy")->fetchColumn();
    }

    function deleteBooks($book_id){
        $con = $this->opencon();
        try{
            $con->beginTransaction();

            $stmtCopies = $con->prepare('DELETE FROM bookcopy WHERE book_id = ?');
            $stmtCopies->execute([$book_id]);
            
            $stmtBG = $con->prepare('DELETE FROM bookgenre WHERE book_id = ?');
            $stmtBG->execute([$book_id]);

            $stmtBA = $con->prepare('DELETE FROM bookauthors WHERE book_id = ?');
            $stmtBA->execute([$book_id]);

            $stmtBook = $con->prepare('DELETE FROM Books WHERE book_id = ?');
            $stmtBook->execute([$book_id]);

            $con->commit();
            return true;
        }catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function deleteAuthor($author_id){
        $con = $this->opencon();
        try{
            $con->beginTransaction();

            $stmtBAs = $con->prepare('DELETE FROM bookauthors WHERE author_id = ?');
            $stmtBAs->execute([$author_id]);

            $stmtAuthor = $con->prepare('DELETE FROM authors WHERE author_id = ?');
            $stmtAuthor->execute([$author_id]);

            $con->commit();
            return true;
        }catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function deleteGenre($genre_id){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            
            $stmtBG = $con->prepare('DELETE FROM bookgenre WHERE genre_id = ?');
            $stmtBG->execute([$genre_id]);

            $stmtBook = $con->prepare('DELETE FROM genres WHERE genre_id = ?');
            $stmtBook->execute([$genre_id]);

            $con->commit();
            return true;
        }catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function getActiveBorrowers(){
        $con = $this->opencon();
        return $con->query("SELECT 
        borrower_id, 
        CONCAT(borrower_firstname, ' ', borrower_lastname) AS borrower_name 
        FROM borrowers 
        WHERE is_active = 1")->fetchAll();
    }

    function getAvailableCopies(){
        $con = $this->opencon();
        return $con->query("SELECT 
        bookcopy.copy_id,
        books.book_id,
        books.book_title
        FROM books
        JOIN bookcopy ON books.book_id = bookcopy.book_id
        WHERE bookcopy.bc_status = 'AVAILABLE'
        ORDER BY books.book_title")->fetchAll();
    }

    function getOnLoanItem(){
        $con = $this->opencon();
        return $con->query("SELECT loanitem.loan_item_id, 
        books.book_title, 
        loanitem.li_duedate,
        loanitem.li_returned_at, 
        loan.loan_status 
        FROM loanitem
        JOIN loan ON loan.loan_id = loanitem.loan_id
        JOIN bookcopy ON bookcopy.copy_id = loanitem.copy_id
        JOIN books ON books.book_id = bookcopy.book_id
        WHERE loanitem.li_returned_at IS NULL AND loan.loan_status = 'Open'")->fetchAll();
    }

    
}

        
?>