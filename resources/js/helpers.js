import moment from "moment-timezone";
import store from "./store/store";
import {router} from "./routes/routes";
import {auth} from "./auth/Authentication";

export function expectedEnvironments(project) {
    if (!project) {
        return []
    }

    const production = {
        name: project.productionBranch,
        branch: project.productionBranch,
        url: '',
        type: 'Production',
        deployments: [],
        tasks: [],
        status: 'New',
    }

    const stages = project.stageBranches.map(stage => ({
        name: stage,
        branch: stage,
        url: '',
        type: 'Stage',
        deployments: [],
        tasks: [],
        status: 'New',
    }))

    // Locator to make applying lagoon info easier
    const stageWithBranch = branch => production.branch === branch ? production : stages.filter(e => e.branch === branch)[0]
    if (project.lagoonProject && project.lagoonProject.environments && project.lagoonProject.environments.length) {
        for (const environment of project.lagoonProject.environments) {
            const stage = stageWithBranch(environment.deployBaseRef)
            if (stage) {
                const deployments = environment.deployments ? [...environment.deployments] : []
                const firstDeployment = deployments[0]


                const statusMap = {
                    'complete': 'Running',
                    'running': 'Deploying',
                    'pending': 'Deploying',
                }

                if (firstDeployment) {
                    stage.status = Object.keys(statusMap).indexOf(firstDeployment.status) !== -1 ? statusMap[firstDeployment.status] : firstDeployment.status
                } else {
                    stage.status = 'Created'
                }
                stage.url = environment.route !== 'undefined' ? environment.route : ''
                stage.deployments = deployments
                stage.tasks = environment.tasks ? [...environment.tasks] : []
            }
        }
    }

    return [
        production,
        ...stages
    ]
}

export function dateFormat(date) {
    return moment.tz(date, 'UTC').tz(moment.tz.guess()).format('DD MMM YYYY h:mm:ss a')
}

export function timeSince(date) {
    return moment.tz(date, 'UTC').tz(moment.tz.guess()).from(moment())
}

export function prefixedId(id, prefix) {

    if (typeof id === "number") {
        return prefix + id
    }

    if (typeof id === "string" && id.substr(0, prefix.length) !== prefix) {
        return prefix + id
    }

    return id
}

export function hostingProjectId(id) {
    return prefixedId(id, '/hosting_projects/')
}

export function deployId(id) {
    return prefixedId(id, '/deploys/')
}

export const io = {
    log: (...args) => io._call(console.log, args),
    error: (...args) => io._call(console.error, args),
    group: (options, callback) => {
        io._call(console.group, [options])
        io._call(callback, [io])
        io._call(console.groupEnd)
    },
    groupCollapsed: (options, callback) => {
        io._call(console.groupCollapsed, [options])
        io._call(callback, [io])
        io._call(console.groupEnd)
    },
    _call: (func, args) => inDev(() => func(...(args || [])))
}

io.group('Mangrove Hosting Control Panel', function() {
    io.log('Welcome to Mangrove Hosting Control Panel');
})

/**
 * @callback emptyCallback
 */

/**
 * Run a function if running in dev
 * @param {emptyCallback=} callback
 */
export function inDev(callback) {
    if (typeof callback === "undefined") {
        return process.env.NODE_ENV !== 'production'
    }

    if (inDev()) {
        return callback()
    }
}

/**
 * Run a function if running in production
 * @param {emptyCallback=} callback
 */
export function inProduction(callback) {
    if (typeof callback === "undefined") {
        return process.env.NODE_ENV === 'production'
    }

    if (inProduction()) {
        return callback()
    }
}

/**
 * Add toast to be shown
 * @param {string} title
 * @param {number=} timeout
 * @param {string=} type
 * @param {boolean=} dismissable
 * @param {string=} id
 */
export function addToast(title, timeout, type, dismissable, id) {
    timeout = timeout ? timeout : 8
    type = type ? type : 'normal'
    dismissable = typeof dismissable === 'boolean' ? dismissable : true
    store.commit('addToast', {title: title, timeout, type, id, canDismiss: dismissable})
}

/**
 * Add toast to be shown only in dev
 * @param {string} title
 * @param {number=} timeout
 * @param {string=} type
 * @param {boolean=} dismissable
 * @param {string=} id
 */
export function addDevToast(title, timeout, type, dismissable, id) {
    inDev(() => addToast(`<strong>[DEV]</strong> ${title}`, timeout, type, dismissable, id));
}

export function validateSessionUpdate(key) {
    return (result) => {
        if (validateSession(result.session)) {
            return result[key]
        }

        return null
    }
}

function arraysSame(arr1, arr2) {
    for (const i of arr1) {
        if (arr2.indexOf(i) === -1) {
            return false
        }
    }

    for (const i of arr2) {
        if (arr1.indexOf(i) === -1) {
            return false
        }
    }

    return true
}

export function validateSession(session) {
    if (session && session.roles.length > 0) {
        const sessionRoles = [...session.roles]
        const storeRoles = [...store.state.roles]

        if (!arraysSame(sessionRoles, storeRoles)) {
            store.commit('setRoles', sessionRoles)
        }

        return true
    }

    auth.logout()
    router.replace('/api-login')
}