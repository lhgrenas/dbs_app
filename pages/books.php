<?php
require_once('../classes/database.php');
 

$con = new database();

$allbooks = $con->viewBooks();
$bookauthor = $con->viewAuthors();
$listedGenre = $con->viewGenre();

$bookCreateStatus = null;
$bookCreateMessage = '';

$addCopyCreateStatus = null;
$addCopyCreateMessage = '';

$assignCreateStatus = null;
$assignCreateMessage = '';

$genreCreateStatus = null;
$genreCreateMessage = '';

if(isset($_POST['add_book'])) {
  $Book_title = $_POST['book_title'];
  $Book_isbn = $_POST['book_isbn'];
  $Book_publication_year = $_POST['book_publication_year'];
  $Book_edition = $_POST['book_edition'];
  $Book_publisher = $_POST['book_publisher'];
  try {
    $con->insertBook($Book_title, $Book_isbn, $Book_publication_year, $Book_edition, $Book_publisher);
    $bookCreateStatus = 'success';
    $bookCreateMessage = 'Book created successfully.';
  } catch(Exception $e) {
    $bookCreateStatus = 'error';
    $bookCreateMessage = 'Error creating book.';
  }
}

if(isset($_POST['add_copy'])) {
  $book_id = $_POST['book_id'];
  $status = $_POST['status'];
  try {
    $con->insertBookCopy($book_id, $status);
    $addCopyCreateStatus = 'success';
    $addCopyCreateMessage = 'Copy added successfully.';
  } catch(Exception $e) {
    $addCopyCreateStatus = 'error';
    $addCopyCreateMessage = 'Error adding copy.';
  }
}

if(isset($_POST['assign_author'])) {
  $book_id = $_POST['book_id'];
  $author_id = $_POST['author_id'];
  try {
    $con->insertBookAuthor($book_id, $author_id);
    $assignCreateStatus = 'success';
    $assignCreateMessage = 'Author assigned successfully.';
  } catch(Exception $e) {
    $assignCreateStatus = 'error';
    $assignCreateMessage = 'Error assigning author.';
  }
}

if(isset($_POST['assign_genre'])) {
  $genre_id = $_POST['genre_id'];
  $book_id = $_POST['book_id'];
  try {
    $con->insertBookGenre($genre_id, $book_id);
    $genreCreateStatus = 'success';
    $genreCreateMessage = 'Genre assigned successfully.';
  } catch(Exception $e) {
    $genreCreateStatus = 'error';
    $genreCreateMessage = 'Error assigning genre.';
  }
}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Books — Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">

</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBooks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navBooks" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.php">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h5 class="mb-1">Add Book</h5>
        <p class="small-muted mb-3">Creates a row in <b>Books</b>.</p>

        <!-- Later in PHP: action="../php/books/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" name="book_title" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" name="book_isbn" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" name="book_publication_year" type="number" min="1500" max="2100" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Edition</label>
            <input class="form-control" name="book_edition" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" name="book_publisher" placeholder="optional">
          </div>
          <button name="add_book" class="btn btn-primary w-100" type="submit">Save Book</button>
        </form>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-2">Add Copy</h6>
        <p class="small-muted mb-3">Creates a row in <b>BookCopy</b>.</p>
        <!-- Later in PHP: action="../php/copies/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Book</label>
            <select class="form-select" name="book_id" required>
              <option value="">Select book</option>
              <?php foreach($allbooks as $books){
                echo '<option value="'.$books['Book_ID'].'">['.$books['Book_ID'].'] '.$books['Book_title'].'</option>';
              } ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="AVAILABLE">AVAILABLE</option>
              <option value="ON_LOAN">ON_LOAN</option>
              <option value="LOST">LOST</option>
              <option value="DAMAGED">DAMAGED</option>
              <option value="REPAIR">REPAIR</option>
            </select>
          </div>
          <button name="add_copy" class="btn btn-outline-primary w-100" type="submit">Add Copy</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-3">
          <div>
            <h5 class="mb-1">Books List</h5>
            <div class="small-muted">Placeholder rows. Replace with PHP + MySQL output.</div>
          </div>
          <div class="d-flex gap-2">
            <input class="form-control" style="max-width: 260px;" placeholder="Search title / ISBN...">
            <button class="btn btn-outline-secondary">Search</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Publisher</th>
                <th>Copies</th>
                <th>Available</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php

            $viewCopies = $con->viewBooks();
            foreach($viewCopies as $vw){

            echo '<tr>';
            echo '<td>'.$vw['Book_ID']. '</td>';
            echo '<td>'.$vw['Book_title']. '</td>';
            echo '<td>'.$vw['Book_isbn']. '</td>';
            echo '<td>'.$vw['Book_publication_year']. '</td>';
            echo '<td>'.$vw['Book_publisher']. '</td>';
            echo '<td><span class="badge bg-primary">'.$vw['Copies'].'</span></td>';
            echo '<td><span class="badge bg-success">'.$vw['Available_Copies'].'</span></td>';
            echo '<td class="text-end">';
            echo '<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBookModal">Edit</button>';
            echo' <button class="btn btn-sm btn-outline-danger">Delete</button>';
                
            echo ' </td>';
            echo ' </tr>';
            
              }
            ?>
            </tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Author to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookAuthors</b>.</p>
              <!-- Later in PHP: action="../php/bookauthors/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                    <?php foreach($allbooks as $books){
                      echo '<option value="'.$books['Book_ID'].'">['.$books['Book_ID'].'] '.$books['Book_title'].'</option>';
                    } ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="author_id" required>
                    <option value="">Select author</option>
                    <?php foreach($bookauthor as $authors){
                      echo '<option value="'.$authors['Author_ID'].'">['.$authors['Author_ID'].'] '.$authors['Author_firstname'].' '.$authors['Author_lastname'].'</option>';
                    } ?>
                  </select>
                </div>
                <div class="col-12">
                  <button name="assign_author" class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (book_id, author_id).</div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Genre to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookGenre</b>.</p>
              <!-- Later in PHP: action="../php/bookgenre/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                    <?php foreach($allbooks as $books){
                      echo '<option value="'.$books['Book_ID'].'">['.$books['Book_ID'].'] '.$books['Book_title'].'</option>';
                    } ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="genre_id" required>
                    <option value="">Select genre</option>
                    <?php foreach($listedGenre as $genres){
                      echo '<option value="'.$genres['Genre_ID'].'">['.$genres['Genre_ID'].'] '.$genres['Genre_Name'].'</option>';
                    } ?>
                  </select>
                </div>
                <div class="col-12">
                  <button name="assign_genre" class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (genre_id, book_id).</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<!-- Edit Book Modal (UI only) -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Book</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" value="Noli Me Tangere">
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" value="9789710810736">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" value="National Book Store">
          </div>
          <button class="btn btn-primary w-100" type="button">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>
<script>
  const bookCreateStatus = <?php echo json_encode($bookCreateStatus)?>;
  const bookCreateMessage = <?php echo json_encode($bookCreateMessage)?>;
  const addCopyCreateStatus = <?php echo json_encode($addCopyCreateStatus)?>;
  const addCopyCreateMessage = <?php echo json_encode($addCopyCreateMessage)?>;
  const assignCreateStatus = <?php echo json_encode($assignCreateStatus)?>;
  const assignCreateMessage = <?php echo json_encode($assignCreateMessage)?>;
  const genreCreateStatus = <?php echo json_encode($genreCreateStatus)?>;
  const genreCreateMessage = <?php echo json_encode($genreCreateMessage)?>;

  if(bookCreateStatus === 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: bookCreateMessage,
      confirmButtonText: 'OK'
    });
  } else if(bookCreateStatus === 'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: bookCreateMessage,
      confirmButtonText: 'OK'
    });
  }

  if(addCopyCreateStatus === 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: addCopyCreateMessage,
      confirmButtonText: 'OK'
    });
  } else if(addCopyCreateStatus === 'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: addCopyCreateMessage,
      confirmButtonText: 'OK'
    });
  }

  if(assignCreateStatus === 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: assignCreateMessage,
      confirmButtonText: 'OK'
    });
  } else if(assignCreateStatus === 'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: assignCreateMessage,
      confirmButtonText: 'OK'
    });
  }

  if(genreCreateStatus === 'success'){
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: genreCreateMessage,
      confirmButtonText: 'OK'
    });
  } else if(genreCreateStatus === 'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: genreCreateMessage,
      confirmButtonText: 'OK'
    });
  }
</script>
  </body>
</html>