import React, {useState} from "react";
import {Button, Label, Modal, TextInput} from "flowbite-react";
import axios from "axios";

export function LoginPage() {
  const [openModal, setOpenModal] = useState(true);
  const [email, setEmail] = useState("");
  const [password, setPassword]  = useState("");
  const [isLoggedIn, setIsLoggedIn] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({email:"",password:""});


  const handleSubmit = async (event) => {
    event.preventDefault();
    setIsLoading(true);

    try {
      const response = await axios.post("http://127.0.0.1:8000/api/login",{email:email, password:password},{
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body:JSON.stringify({email:email,password:password})
      });

      if (response.data.status === 401) {
        alert("User failed to login")
      }

      if (response.status === 200) {
        setIsLoggedIn(true);
        // console.log(response.data.status);
        alert("User logged in successfully");
      } 
    } catch (error) {
      alert("Failed to login user");
    } finally {
      setIsLoading(false);
      setOpenModal(false);
    }
    console.log(email, password)
  }

  function onCloseModal(e) {
      e.preventDefault();
      setOpenModal(false);
      setEmail('');
      setPassword('');
  }

  return (
      <Modal show={openModal} size="md" onClose={onCloseModal} popup>
      <Modal.Header />
      <form onSubmit={handleSubmit}>
      <Modal.Body>
        <div className="space-y-6">
          <h3 className="text-xl font-medium text-gray-900 dark:text-white">Sign in to our platform</h3>
          <div>
            <div className="mb-2 block">
              <Label htmlFor="email" value="Your email" />
            </div>
            <TextInput
              id="email"
              name="email"
              placeholder="name@company.com"
              value={email}
              onChange={(e)=> setEmail(e.target.value)}
              required
            />
          </div>
          <div>
            <div className="mb-2 block">
              <Label htmlFor="password" value="Your password" />
            </div>
            <TextInput
                id="password" 
                type="password" 
                name="password"
                value={password}
                onChange={(e)=> setPassword(e.target.value)}
                required />
          </div>
          <div className="w-full">
            <Button type="submit">Log in to your account</Button>
          </div>
          <div className="flex justify-between text-sm font-medium text-gray-500 dark:text-gray-300">
            Not registered?&nbsp;
            <a href="#" className="text-cyan-700 hover:underline dark:text-cyan-500">
              Create account
            </a>
          </div>
        </div>
      </Modal.Body>
      </form>
    </Modal>
  );
}
