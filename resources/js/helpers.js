import moment from "moment-timezone";

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
    _call: (func, args) => process.env.NODE_ENV !== 'production' ? func(...(args || [])) : null
}

io.group('Mangrove Hosting Control Panel', function() {
    io.log('Welcome to Mangrove Hosting Control Panel');
})
