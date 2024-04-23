import { createBrowserRouter } from "react-router-dom";
import Home from "../pages/home.jsx"; 
import Login from "../pages/login.jsx"; 
import Signup from "../pages/signup.jsx";
import Wallets from "../pages/wallets.jsx";
import NoteFound from "../pages/notFound.jsx";
import Layout from "../layouts/layout.jsx";
export const router = createBrowserRouter([
    {
        element: <Layout/>,
        children : [
            {
                path: "/",
                element: <Home/>
            },
            {
                path: "/login",
                element: <Login/>
            },
            {
                path: "/signup",
                element: <Signup/>
            },
            {
                path: "/wallets",
                element: <Wallets/>
            },
            {
                path: "*",
                element: <NoteFound/>
            }
        ]
    }
    
]);
