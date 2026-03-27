import { useState } from 'react'
import {Navbar} from "./components/Navbar";
import {Routes, Route} from "react-router-dom";
import {Home} from "./pages/Home";
import {Dashboard} from "./pages/Dashboard";

import './App.css'

function App() {
  const [count, setCount] = useState(0)

  return (
    <div className="App">
      <Navbar />
      {/* Routes With Components */}
       <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/dashboard" element={<Dashboard />} />
      </Routes>
    </div>
  )
}

export default App
