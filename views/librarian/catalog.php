<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="color: #2c3e50;">Librarian Dashboard: Catalog Management</h2>

        <div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">

            <div style="flex: 1; min-width: 300px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3>Genres</h3>
                <form action="index.php?action=librarian_dashboard" method="POST" style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <input type="hidden" name="action_type" value="add_genre">
                    <input type="text" name="genre_name" placeholder="New Genre Name" required style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <button type="submit" style="padding: 8px 15px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer;">Add</button>
                </form>

                <table style="width: 100%; border-collapse: collapse;">
                    <?php foreach($genres as $genre): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px 0;"><?php echo htmlspecialchars($genre['name']); ?></td>
                            <td style="text-align: right;">
                                <button onclick="editGenre(<?php echo $genre['id']; ?>, '<?php echo addslashes($genre['name']); ?>')" style="padding: 5px 10px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 5px;">Edit</button>
                                <form action="index.php?action=librarian_dashboard" method="POST" style="display:inline;">
                                    <input type="hidden" name="action_type" value="delete_genre">
                                    <input type="hidden" name="genre_id" value="<?php echo $genre['id']; ?>">
                                    <button type="submit" style="padding: 5px 10px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div style="flex: 2; min-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 id="form-title">Add New Book</h3>
                    <button id="cancel-edit-btn" onclick="resetForm()" style="display: none; padding: 5px 10px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Cancel Edit</button>
                </div>

                <form id="book-form" action="index.php?action=librarian_dashboard" method="POST">
                    <input type="hidden" name="action_type" id="form-action-type" value="add_book">
                    <input type="hidden" name="book_id" id="form-book-id" value="">

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:5px;">Title</label>
                            <input type="text" name="title" id="form-title-input" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:5px;">Author</label>
                            <input type="text" name="author" id="form-author" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:5px;">Genre</label>
                            <select name="genre_id" id="form-genre" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                                <?php foreach($genres as $genre): ?>
                                    <option value="<?php echo $genre['id']; ?>"><?php echo htmlspecialchars($genre['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:5px;">ISBN (10 or 13 digits)</label>
                            <input type="text" name="isbn" id="form-isbn" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:bold; margin-bottom:5px;">Total Copies</label>
                            <input type="number" name="total_copies" id="form-copies" min="1" value="1" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <label style="display:block; font-weight:bold; margin-bottom:5px;">Shelf</label>
                                <input type="text" name="shelf_location" id="form-shelf" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                            </div>
                            <div style="flex: 1;">
                                <label style="display:block; font-weight:bold; margin-bottom:5px;">Year</label>
                                <input type="number" name="published_year" id="form-year" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="form-submit-btn" style="margin-top: 15px; width: 100%; padding: 10px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Save Book to Catalog</button>
                </form>
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;">Book Catalog</h3>
                <input type="text" id="catalog-search" placeholder="Search by Title, Author, or ISBN..." onkeyup="liveSearch(this.value)" style="width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                <tr style="background: #f8f9fa; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">ISBN</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Title</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Author</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Genre</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd; text-align: center;">Total / Available</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">Action</th>
                </tr>
                </thead>
                <tbody id="book-table-body">
                <?php foreach($books as $book): ?>
                    <tr style="border-bottom: 1px solid #eee; <?php echo ($book['available_copies'] <= 0) ? 'background-color: #fdeaea;' : ''; ?>">
                        <td style="padding: 12px;"><?php echo htmlspecialchars($book['isbn']); ?></td>
                        <td style="padding: 12px;"><strong><?php echo htmlspecialchars($book['title']); ?></strong><br><small style="color: #7f8c8d;">Shelf: <?php echo htmlspecialchars($book['shelf_location']); ?></small></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($book['author']); ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($book['genre_name']); ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <?php echo $book['total_copies']; ?> /
                            <strong style="color: <?php echo ($book['available_copies'] > 0) ? 'inherit' : '#c0392b'; ?>">
                                <?php echo $book['available_copies']; ?>
                            </strong>
                        </td>
                        <td style="padding: 12px; display: flex; gap: 5px;">
                            <?php $bookData = htmlspecialchars(json_encode($book), ENT_QUOTES, 'UTF-8'); ?>
                            <button onclick="editBook(<?php echo $bookData; ?>)" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Edit</button>
                            <form action="index.php?action=librarian_dashboard" method="POST" style="display:inline;">
                                <input type="hidden" name="action_type" value="delete_book">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <button type="submit" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editGenre(id, currentName) {
            let newName = prompt("Edit Genre Name:", currentName);
            if (newName !== null && newName.trim() !== "" && newName !== currentName) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php?action=librarian_dashboard';
                form.innerHTML = `
                <input type="hidden" name="action_type" value="edit_genre">
                <input type="hidden" name="genre_id" value="${id}">
                <input type="hidden" name="genre_name" value="${newName}">
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function editBook(book) {
            document.getElementById('form-action-type').value = 'edit_book';
            document.getElementById('form-book-id').value = book.id;

            document.getElementById('form-title-input').value = book.title;
            document.getElementById('form-author').value = book.author;
            document.getElementById('form-genre').value = book.genre_id;
            document.getElementById('form-isbn').value = book.isbn;
            document.getElementById('form-copies').value = book.total_copies;
            document.getElementById('form-shelf').value = book.shelf_location;
            document.getElementById('form-year').value = book.published_year;

            document.getElementById('form-title').innerText = 'Edit Book';
            let btn = document.getElementById('form-submit-btn');
            btn.innerText = 'Update Book Info';
            btn.style.background = '#f39c12';
            document.getElementById('cancel-edit-btn').style.display = 'block';

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            document.getElementById('book-form').reset();
            document.getElementById('form-action-type').value = 'add_book';
            document.getElementById('form-book-id').value = '';

            document.getElementById('form-title').innerText = 'Add New Book';
            let btn = document.getElementById('form-submit-btn');
            btn.innerText = 'Save Book to Catalog';
            btn.style.background = '#27ae60';
            document.getElementById('cancel-edit-btn').style.display = 'none';
        }

        function liveSearch(query) {
            fetch('index.php?action=api/books/search&q=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('book-table-body');
                    tbody.innerHTML = '';
                    if(data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px;">No books found matching the search criteria.</td></tr>';
                        return;
                    }

                    data.forEach(book => {
                        let available = parseInt(book.available_copies);
                        let rowStyle = available <= 0 ? 'background-color: #fdeaea;' : '';
                        let textStyle = available <= 0 ? 'color: #c0392b;' : 'color: inherit;';
                        let bookJson = JSON.stringify(book).replace(/"/g, '&quot;');

                        let html = `
                        <tr style="border-bottom: 1px solid #eee; ${rowStyle}">
                            <td style="padding: 12px;">${book.isbn}</td>
                            <td style="padding: 12px;"><strong>${book.title}</strong><br><small style="color: #7f8c8d;">Shelf: ${book.shelf_location || ''}</small></td>
                            <td style="padding: 12px;">${book.author}</td>
                            <td style="padding: 12px;">${book.genre_name || ''}</td>
                            <td style="padding: 12px; text-align: center;">
                                ${book.total_copies} / <strong style="${textStyle}">${available}</strong>
                            </td>
                            <td style="padding: 12px; display: flex; gap: 5px;">
                                <button onclick="editBook(${bookJson})" style="padding: 6px 12px; background: #f39c12; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Edit</button>
                                <form action="index.php?action=librarian_dashboard" method="POST" style="display:inline;">
                                    <input type="hidden" name="action_type" value="delete_book">
                                    <input type="hidden" name="book_id" value="${book.id}">
                                    <button type="submit" style="padding: 6px 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                        tbody.innerHTML += html;
                    });
                });
        }
    </script>
<?php include 'views/layout/footer.php'; ?>