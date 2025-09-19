

const API_URL = "http://localhost/student-system/backend/api/auth";

//_Login--

export async function login(username, password) {
    try {
        const response = await fetch(`${API_URL}/login.php`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({username,password})
        });

        const data = await response.json()

        if(!response.ok) {
            throw new Error(data.message || "Login failed");
        }

        //Save token to localStorage
        localStorage.setItem("token", data.token);
        localStorage.setItem("user", JSON.stringify(data.user));

        return data.user;

    } catch (error) {
        console.error("Login error:", error);
    throw error;
    }
}


// --- Logout ---
export async function logout() {
  try {
    const token = localStorage.getItem("token");
    if (!token) return;

    await fetch(`${API_URL}/logout.php`, {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${token}`
      }
    });

    // Remove token and user info from localStorage
    localStorage.removeItem("token");
    localStorage.removeItem("user");
  } catch (error) {
    console.error("Logout error:", error);
  }
}

//--Get current user --
export function getCurrentUser() {
    const user = localStorage.getItem("user");
    return user? JSON.parse(user) : null;
}
