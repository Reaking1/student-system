import React, { useState } from "react";
import {useAuth} from "../hooks/useAuth"
import {useNavigate} from "react-router"
import 'LoginForm.css'
export default function LoginForm() {
    const {login, loading} = useAuth();
    const navigate = useNavigate();
    const [username, setUserName] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");



    const handleSubmit = async (e) => {
        e.preventDefault();
        setError("")
  
    try {

        //we ned to fix this
        const user = await login(username,password);

        //Redirect based on role
        if(user.role === "admin") {
            navigate("/admin/dashboard");
        } else {
            navigate("/student/dashboard");
        }

    } catch (err) {
    setError(err.message || "Login failed");
    }
};


return (
    <div className="login-container">
    <h2 className="login-title">Login</h2>
    {error && <p className="login-error">{error}</p>}
    <form onSubmit={handleSubmit} className="login-form">
        <div className="form-group">
            <label>Username</label>
            <input type="text" placeholder="Enter your username" value={username} onChange={(e) => setUserName(e.target.value)} required/>
        </div>
          <div className="form-group">
            <label>Password</label>
            <input type="password" placeholder="Enter your password" value={password} onChange={(e) => setPassword(e.target.value)} required/>
          </div>
          <button type="submit" disabled={loading}>
            {loading ? "Loggin in..." : "Login"}
          </button>
    </form>
    </div>
)

}