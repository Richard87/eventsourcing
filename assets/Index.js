
import React from "react"
import {useMutation, useQuery} from "react-query";
import {jsonFetch} from "./app";
import {Link} from "react-router-dom";

export default function Index() {
    const {isLoading, isError, data, refetch} = useQuery("/api/buildings")
    const newBuilding = useMutation(newBuilding => jsonFetch("/api/buildings/register_new_building", newBuilding))
    if (isError) return <span>ERROR!!!</span>

    const handleSubmit = async event => {
        event.preventDefault()

        const fd = new FormData(event.target)
        event.target.reset()
        return newBuilding.mutateAsync(Object.fromEntries(fd.entries()),{onSuccess: refetch })
    }

    return <>
        <h1>Register Building</h1>

        <form onSubmit={handleSubmit}>
            <input title="Building name" type="text" name="name" placeholder="Building name"/>
            <button disabled={newBuilding.isLoading}>Register</button>
        </form>
        {isLoading && <span>Loading...</span>}
        <ul>
            {data && data.map(b => <li key={b.uuid}><Link to={`/building/${b.uuid}`}>{b.name}</Link></li>)}
        </ul>

    </>
}
