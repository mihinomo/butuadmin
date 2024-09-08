<?php 


class Book {
    public static function make_book_seq($lid, $aid) {
        if ($aid == 'all') {
            $sql = "SELECT * FROM books WHERE lid = :lid ORDER BY begin ASC";
            $params = [':lid' => $lid];
        } else {
            $sql = "SELECT * FROM books WHERE lid = :lid AND whatsapp = :aid ORDER BY begin ASC";
            $params = [':lid' => $lid, ':aid' => $aid];
        }
    
        $data = DB::executeQuery($sql, $params, false);
    
        if ($data === null) {
            echo "<div class='alert alert-danger' role='alert'>An error occurred during data retrieval.</div>";
        } elseif (empty($data)) {
            echo "<div class='alert alert-warning' role='alert'>No sequences available for the specified criteria.</div>";
        } else {
            foreach ($data as $row) {
                echo '<div class="alert alert-primary bg-primary text-light border-0 fade show" role="alert">
                        Sequence ' . htmlspecialchars($row['begin']) . ' - ' . htmlspecialchars($row['end']) . ' | ' . self::showBookAgent(htmlspecialchars($row['whatsapp'])) . '
                        <a href="/showsequence/' . htmlspecialchars($lid) . '/' . htmlspecialchars($row['id']) . '/" class="btn btn-secondary pb-1" style="float:right; margin-left:1rem">View</a>
                        <a onclick="del('.htmlspecialchars($row['id']).');" class="btn btn-danger pb-1 mr-1" style="float:right;">Delete</a>
                    </div>';
            }
        }
    }
    

    public static function showBookAgent($phone) {
        // Prepare the SQL query using placeholders for parameters
        $sql = "SELECT name FROM agents WHERE phone = :phone";
        $params = [':phone' => $phone];
    
        // Execute the query using the executeQuery method
        // Assuming executeQuery is designed to fetch a single row when true is passed as the third argument
        $row = DB::executeQuery($sql, $params, true);
    
        // Return the agent's name if the query was successful and the agent exists
        if ($row) {
            return htmlspecialchars($row['name']); // Safely encode output to prevent XSS
        } else {
            // Handle case where no data was returned or there was an error
            return "Unknown Agent"; // Or any other default or error message you prefer
        }
    }

    public static function sequence_table($lid, $begin, $end, $book) {
        // Fetch all bookings in the range at once
        $sql = "SELECT * FROM bookings WHERE lid = :lid AND serial BETWEEN :begin AND :end";
        $params = [':lid' => $lid, ':begin' => $begin, ':end' => $end];
        $bookings = DB::executeQuery($sql, $params, false);
    
        // Create a map of existing serial numbers to rows
        $bookingMap = [];
        if ($bookings) {
            foreach ($bookings as $booking) {
                $bookingMap[$booking['serial']] = $booking;
            }
        }
    
        // Now iterate through the range and output appropriate HTML
        $range = range($begin, $end);
        foreach ($range as $serial) {
            if (isset($bookingMap[$serial])) {
                self::table_sold($serial, $bookingMap[$serial], $lid, $book);
            } else {
                self::table_unsold($serial);
            }
        }
    }
    
    public static function table_sold($nos, $row, $lid, $book) {
        echo '<tr>
                <th scope="row">' . htmlspecialchars($nos) . '</th>
                <td>' . htmlspecialchars($row['name']) . '</td>
                <td>' . htmlspecialchars($row['phone']) . '</td>
                <td>' . htmlspecialchars($row['address']) . '</td>
                <td>' . self::gen_paystatus($row['pstatus']) . '</td>';
        
    }
    
    public static function table_unsold($nos) {
        echo '<tr>
                <th scope="row">' . htmlspecialchars($nos) . '</th>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>';
    }
    public static function gen_paystatus($i) {
        if ($i == 1) {
            return '<span style="color:green;">Approved</span>';
        } else {
            return '<span style="color:red;">Pending</span>';
        }
    }
    public static function availableTickets($lid, $agent, $series) {
        $params = ['lid' => $lid];
        $sql = "SELECT b.sl, b.bookno, b.id, b.begin, b.end
                FROM books b
                WHERE b.lid = :lid";
    
        if ($agent != 'none') {
            $sql .= " AND whatsapp = :agent";
            $params['agent'] = $agent;
        }
    
        $sql .= " ORDER BY b.sl ASC";
        $books = DB::query($sql, $params);
    
        // Prepare the response data, organizing it by book and serial range
        $data = [];
        foreach ($books as $book) {
            $bookDetails = [
                'sl' => $book['sl'],
                'bookno' => $book['bookno'],
                'begin' => $book['begin'],
                'end' => $book['end'],
                'bookEntries' => []
            ];
            
            // Get all bookings in range
            $bookedEntries = self::checkBookedInRange($book['begin'], $book['end'], $lid);
            $bookedSerials = array_column($bookedEntries, 'serial'); // Array of booked serials
    
            // Check each serial in the range
            for ($serial = $book['begin']; $serial <= $book['end']; $serial++) {
                $index = array_search($serial, $bookedSerials);
                if ($index !== false) {
                    // Serial is booked
                    $booking = $bookedEntries[$index];
                    $bookDetails['bookEntries'][] = [
                        'serial' => $booking['serial'],
                        'name' => $booking['name'] ?? '--',
                        'address' => $booking['address'] ?? '--',
                        'phone' => $booking['phone'] ?? '--',
                        'status' => $booking['pstatus'] === "1" ? "Paid" : "Unpaid",
                        'agentName' => Emp::agentName($booking['agent'])
                    ];
                } else {
                    // Serial is not booked
                    $bookDetails['bookEntries'][] = [
                        'serial' => $serial,
                        'status' => 'Available'
                    ];
                }
            }
            $data[] = $bookDetails;
        }
    
        return json_encode($data);
    }
    
    public static function checkBookedInRange($begin, $end, $lid) {
        $params = ['begin' => $begin, 'end' => $end, 'lid' => $lid];
        $sql = "SELECT serial, name, address, phone, pstatus, agent FROM bookings WHERE serial BETWEEN :begin AND :end AND lid = :lid";
        return DB::query($sql, $params);
    }
    
    
    
}