<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <title>Profile Settings</title>
</head>
<body>
    <h1>Profile Settings</h1>
    
    <form> 
        <fieldset title="BaseData">
            <legend>Base Data</legend>
            
            <div class="form-group">
                <label for="FirstName">First Name</label>
                <div class="input-wrapper">
                    <input type="text" id="FirstName" name="FirstName" class="coalinged" placeholder="Your name">
                </div>
            </div>

            <div class="form-group">
                <label for="LastName">Last Name</label>
                <div class="input-wrapper">
                    <input type="text" id="LastName" name="LastName" class="coalinged" placeholder="Your surname">
                </div>
            </div>

            <div class="form-group">
                <label for="CorT">Coffee or Tea?</label>
                <div class="input-wrapper">
                    <select id="CorT" name="CorT" defaultValue="NeitherNor" class="coalinged">
                        <option value="NeitherNor">Neither Nor</option>
                        <option value="Coffee">Coffee</option>
                        <option value="Tea">Tea</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset title="AboutYou">
            <legend>Tell Something About You</legend>
            <textarea id="AboutMe" name="AboutMe" rows="4" cols="50" placeholder="Leave a comment here"></textarea>
        </fieldset>

        <fieldset title="PreferredChatLayout">
            <legend>Preferred Chat Layout</legend>
            <p></p>
            <input type="radio" id="OneLine" name="ChatLayout" value="OneLine">
            <label for="OneLine">Username and message in one line</label>
            <p></p>
            <input type="radio" id="SepLines" name="ChatLayout" value="SepLines">
            <label for="SepLines">Username and message in separate lines</label>
            <p></p>
        </fieldset>

        <button type="button" onclick="location.href='freundesliste.php'">Cancel</button>
        <button type="submit">Save</button>
    </form>
</body>
</html>