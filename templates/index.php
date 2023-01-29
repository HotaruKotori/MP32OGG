<html>
  <head>
    <style>
      /* 背景图 */
      body {
        background-image: url(https://img-baofun.zhhainiao.com/fs/00be2c4460422f392bfbfbd3ca45f2ed.jpg);
        background-size: cover;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      /* 进度条 */
      .progress-bar {
        width: 400px;
        height: 20px;
        border: 1px solid black;
        position: relative;
        margin: 20px auto;
      }
      .progress {
        height: 100%;
        background-color: green;
        width: 0%;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <form>
        <input type="file" id="mp3_file" />
        <button type="button" id="convert-btn">转换</button>
      </form>
      <div class="progress-bar">
        <div class="progress"></div>
      </div>
    </div>
    <script>
      const convertBtn = document.querySelector("#convert-btn");
      const mp3FileInput = document.querySelector("#mp3_file");
      const progressBar = document.querySelector(".progress");

      convertBtn.addEventListener("click", async () => {
        const mp3File = mp3FileInput.files[0];
        const formData = new FormData();
        formData.append("mp3_file", mp3File);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/convert", true);
        xhr.responseType = "arraybuffer";

        xhr.upload.onprogress = (event) => {
          const percentComplete = (event.loaded / event.total) * 100;
          progressBar.style.width = `${percentComplete}%`;
        };

        xhr.onload = function () {
          if (xhr.status === 200) {
            const oggFile = new Blob([xhr.response], { type: "audio/ogg" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(oggFile);
            link.download = "converted.ogg";
            link.click();
          }
        };

        xhr.send(formData);
      });
    </script>
  </body>
</html>
