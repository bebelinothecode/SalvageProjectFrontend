import React, {useState} from "react";
import {Button, Label, Modal, TextInput} from "flowbite-react";
import axios from "axios";
import { useAuth } from "../../Context";

export const  LoginPage =() => {
  const [openModal, setOpenModal] = useState(true);
  const [isLoggedIn, setIsLoggedIn] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState({email:"",password:""});
  const {setIsLoggedInTo, setUser} = useAuth();

  const handleChange = (event) => {
    const {name, value} = event.target;
    event.preventDefault();
    setFormData((prevFormData) => ({ ...prevFormData, [name]: value }));
  }

  const handleSubmit = async (event) => {
    event.preventDefault();
    setIsLoading(true);

    try {
      const response = await axios.post("http://127.0.0.1:8000/api/login",formData,{
        method:"POST",
        headers:{"Content-Type":"application/json"},
        body:JSON.stringify(formData)
      });

      console.log(`Response:${response.body}`)

      if (response.data.status === 401) {
        alert("User failed to login")
      }

      if (response.status === 200) {
        setIsLoggedIn(true);
        setIsLoggedInTo(true);
        setUser({email:formData.email, password:formData.password})
        alert("User logged in successfully");
      } 
    } catch (error) {
      alert("Failed to login user");
    } finally {
      setIsLoading(false);
      setOpenModal(false);
    }
    console.log(formData)
  }

  function onCloseModal(e) {
      e.preventDefault();
      setOpenModal(false);
      setFormData({email:"", password:""})
  }

  return (
    <div>
      {
      isLoggedIn ? 
      alert("User logged in successfully") :
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
              value={formData.email}
              onChange={handleChange}
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
                value={formData.password}
                onChange={handleChange}
                required />
          </div>
          {
            isLoading ? (
              <button disabled type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 inline-flex items-center">
                  <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                  <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                  </svg>
                  Loading...
              </button> 
            ) : 
            <>
              <div className="w-full">
                <Button type="submit">Log in to your account</Button>
              </div>
            </>
          }
          {/* <div className="w-full">
            <Button type="submit">Log in to your account</Button>
          </div> */}
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
      }
      {/* <Modal show={openModal} size="md" onClose={onCloseModal} popup>
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
      </Modal> */}
    </div>
  );
}
