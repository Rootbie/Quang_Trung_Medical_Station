import firebase from 'firebase'

const firebaseConfig = {
    apiKey: "AIzaSyAtiFx3NPMwf62gUrZb6CVrHXG8XPBRDCU",
    authDomain: "quang-trung-medical-station.firebaseapp.com",
    projectId: "quang-trung-medical-station",
    storageBucket: "quang-trung-medical-station.appspot.com",
    messagingSenderId: "526604777650",
    appId: "1:526604777650:web:c9cd356c3b6cde420a71ab",
    measurementId: "G-XFSC999EJ7"
} 

firebase.initializeApp(firebaseConfig);

export default firebase;