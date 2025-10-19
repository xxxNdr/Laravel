

<!-- ####################### -->
<!-- ###   FLUSSO AJAX   ###-->
<!-- ####################### -->



1. L'UTENTE CLICCA UN BOTTONE NEL BROWSER:

        ```html
        <button id="calcola-provvigioni-btn">Calcola Provvigioni</button>
        ```

2. JAVASCRIPT FA LA CHIAMATA AJAX (file.js)
   invia una richiesta HTTP POST a Laravel

        ```js
        const response = await fetch('/calcola-provvigioni', { // questa è la route
            method: 'POST',
            headers: {...}
        });
        ```

3. LARAVEL RICEVE LA RICHIESTA (route.php)
   la route indirizza al metodo calcolaProvvigioni() nel Controller

        ```php
        Route::post('/calcola-provvigioni', [VenditaController::class, 'calcolaProvvigioni']);
        ```

4. IL CONTROLLER ESEGUE LO SCRIPT PYTHON E RISPONDE (Controller.php)
   Laravel invia indietro un JSON come risposta HTTP

        ```php
        public function calcolaProvvigioni()
        {
            shell_exec('py calcolo_provvigioni.py'); // Esegue Python

            return response()->json([
                'success' => true,
                'message' => 'Provvigioni calcolate con successo'
            ]);
        }
        ```

5. JAVASCRIPT RICEVE IL JSON (file.js)

        ```js
        const data = await response.json(); // Converte la risposta in oggetto JS

        // Ora puoi leggere i dati:
        console.log(data.success); // true
        console.log(data.message); // "Provvigioni calcolate con successo"

        // Eventualmente mostri all'utente:
        alert(data.message);
        ```
