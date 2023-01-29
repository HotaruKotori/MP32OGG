<html>
    <head>
        <style>
            #container {
                width: 100vw;
                height: 100vh;
                margin: 0 auto;
                background-image: url(https://img-baofun.zhhainiao.com/fs/00be2c4460422f392bfbfbd3ca45f2ed.jpg);
                background-size: cover;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            #progress-bar {
                width: 80%;
                height: 10px;
                margin-top: 20px;
                background-color: lightgray;
                border-radius: 10px;
                position: relative;
            }

            #progress {
                width: 0;
                height: 100%;
                background-color: blue;
                border-radius: 10px;
                position: absolute;
                left: 0;
                top: 0;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <h1>MP3 to OGG Converter</h1>
            <form id="form">
                <input type="file" id="mp3_file" name="mp3_file" accept="audio/mp3">
                <button id="convert-button" type="button">Convert</button>
            </form>
            <div id="progress-bar">
                <div id="progress"></div>
            </div>
        </div>
        <script>
            const form = document.getElementById('form');
            const convertButton = document.getElementById('convert-button');
            const progressBar = document.getElementById('progress-bar');
            const progress = document.getElementById('progress');

            convertButton.addEventListener('click', () => {
                const mp3File = form.elements.mp3_file.files[0];

                if (!mp3File) {
                    alert('Please select an MP3 file to convert.');
                    return;
                }

                const formData = new FormData();
                formData.append('mp3_file', mp3File);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/convert', true);

                xhr.upload.onprogress = (event) => {
                    if (event.lengthComputable) {
                        const percent = (event.loaded / event.total) * 100;
                        progress.style.width = `${percent}%`;
                    }
                };

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        const oggFile = new Blob([xhr.response], { type: 'audio/ogg' });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(oggFile);
                        link.download = `${mp3File.name.split('.')[0]}.ogg`;
                        link.click();
                    } else {
                        alert(`Failed to convert MP3 to OGG. Error: ${xhr.responseText}`);
                    }
                };

                xhr.send(formData);
            });
        </script>
    </body>
</html>