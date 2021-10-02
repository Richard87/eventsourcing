
import React from "react"
import {useMutation, useQuery} from "react-query";
import {jsonFetch} from "./app";
import {useParams} from "react-router";
import {Link} from "react-router-dom";

export default function Details() {
    const {uuid} = useParams()
    const {isLoading, isError, data: building = {}, refetch} = useQuery(`/api/buildings/${uuid}`)
    const checkIn = useMutation(user => jsonFetch("/api/buildings/check_in_user", user))
    const checkOut = useMutation(user => jsonFetch("/api/buildings/check_out_user", user))
    if (isError) return <span>ERROR!!!</span>
    if (isLoading) return <span>Loading...</span>

    const handleCheckin = async event => {
        event.preventDefault()

        const fd = new FormData(event.target)
        event.target.reset()
        let user = Object.fromEntries(fd.entries());
        return checkIn.mutateAsync({...user, uuid},{onSuccess: refetch })
    }

    const handleCheckout = async username => {
        return checkOut.mutateAsync({username, uuid},{onSuccess: refetch })
    }

    return <>
        <h1>Welcome to { building.name }</h1>
        <Link to="/">Tilbake</Link>

        <h2>Check In: </h2>
        <form  onSubmit={handleCheckin}>
            <input type="text" name="username" placeholder="Enter your username to checkin" required="required"/>

            <button disabled={checkIn.isLoading}>CheckIn</button>
        </form>
        <ul>
            {building.users.map(u => (
                <li key={u.name}>
                    <span style={{textDecoration: u.checkedOut ? "underline" : "strikethrough"}}>{u.name}</span>
                    {u.checkedIn && <button onClick={() => handleCheckout(u.name)}>Checkout</button>}
                </li>
            ))}
        </ul>

    </>
}
