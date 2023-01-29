import os

from flask import Flask, request, jsonify, render_template
from pydub import AudioSegment
from flask import send_file,make_response
from werkzeug.utils import secure_filename

app = Flask(__name__)

'''@app.route('/convert', methods=['POST'])
def convert():
    mp3_file = request.files['mp3_file']
    mp3_file.save('input.mp3')
    sound = AudioSegment.from_mp3("input.mp3")
    sound.export("output.ogg", format="ogg")
    response = {'status': 'success'}
    return jsonify(response)'''
@app.route('/convert', methods=['POST'])
def convert():
    mp3_file = request.files['mp3_file']
    if mp3_file:
        mp3_path = secure_filename(mp3_file.filename)
        mp3_file.save(mp3_path)
        print(f"mp3_path:{mp3_path}")
        try:
            sound = AudioSegment.from_mp3(mp3_path)
            sound.export('output.ogg', format="ogg")
            response = make_response(send_file('output.ogg', as_attachment=True))
            response.headers["Content-Disposition"] = "attachment; filename=converted.ogg"
            return response
        except Exception as e:
            print(e)
            return "Error while converting file"
        finally:
            os.remove(mp3_path)
    else:
        return "Error while uploading file"

@app.route('/')
def index():
    return render_template('index.php')

if __name__ == '__main__':
    app.run(debug=True)
