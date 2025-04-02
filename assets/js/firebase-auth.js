import { getAuth, signInWithPopup, GoogleAuthProvider } from "firebase/auth";

const auth = getAuth();
const provider = new GoogleAuthProvider();

document.getElementById("google-login").addEventListener("click", () => {
    signInWithPopup(auth, provider)
        .then((result) => {
            fetch('/api/auth.php', {
                method: 'POST',
                body: JSON.stringify({ email: result.user.email }),
                headers: { 'Content-Type': 'application/json' }
            });
        })
        .catch(error => console.error("Error logging in:", error));
});
